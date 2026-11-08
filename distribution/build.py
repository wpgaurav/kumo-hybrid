#!/usr/bin/env python3
"""Reproducible clean and free-license updater builds for Kumo Hybrid."""
from pathlib import Path
import argparse,hashlib,json,re,tempfile,zipfile,shutil
ROOT=Path(__file__).resolve().parents[1]
p=argparse.ArgumentParser();p.add_argument('--channel',choices=['clean','direct','all'],default='all');p.add_argument('--out',type=Path,default=ROOT/'build');args=p.parse_args()
style=(ROOT/'style.css').read_text();version=re.search(r'^Version:\s*(.+)$',style,re.M)[1].strip();slug='kumo-hybrid'
assert re.fullmatch(r'\d+\.\d+\.\d+',version)
assert 'Update URI:' not in style and not (ROOT/'inc/class-kumo-hybrid-license.php').exists()
manifest={}
for channel in (['clean','direct'] if args.channel=='all' else [args.channel]):
 with tempfile.TemporaryDirectory(prefix='kumo-package-') as temporary:
  stage=Path(temporary)/slug;stage.mkdir()
  for item in ROOT.iterdir():
   if item.is_dir() and item.name in ['inc','assets','languages','patterns','page-templates','template-parts']:shutil.copytree(item,stage/item.name)
   elif item.is_file() and (item.suffix=='.php' or item.name in ['style.css','theme.json','readme.txt','README.md','LICENSE','THIRD-PARTY-NOTICES.md','screenshot.png']):shutil.copy2(item,stage/item.name)
  if channel=='direct':
   shutil.copy2(ROOT/'distribution/class-kumo-hybrid-license.php',stage/'inc/class-kumo-hybrid-license.php')
   (stage/'style.css').write_text(style.replace('Version: '+version,'Version: '+version+'\nUpdate URI: https://gauravtiwari.org/product/kumo-hybrid/#license'))
   fn=stage/'functions.php';fn.write_text(fn.read_text()+"\n// Free-license updates for the direct-download distribution.\nrequire_once __DIR__ . '/inc/class-kumo-hybrid-license.php';\nKumo_Hybrid_License::boot();\n")
   readme=stage/'readme.txt';readme.write_text(readme.read_text()+'\n== Updates in this download ==\nA free lifetime key for unlimited websites is included with checkout at https://gauravtiwari.org/product/kumo-hybrid/. Add it under Appearance > Kumo Hybrid License to receive updates. Every theme feature works without a key. License checks send the key, theme version, and website address to gauravtiwari.org. Automatic installation follows your WordPress auto-update preference.\n')
  target=args.out/(slug+'-'+version+('-licensed' if channel=='direct' else '')+'.zip');target.parent.mkdir(exist_ok=True,parents=True)
  with zipfile.ZipFile(target,'w',zipfile.ZIP_DEFLATED,compresslevel=9) as z:
   for file in sorted(stage.rglob('*')):
    if file.is_file():
     info=zipfile.ZipInfo(str(file.relative_to(stage.parent)),(2026,1,1,0,0,0));info.compress_type=zipfile.ZIP_DEFLATED;info.external_attr=0o100644<<16;z.writestr(info,file.read_bytes())
  with zipfile.ZipFile(target) as z:
   assert z.testzip() is None;names=z.namelist()
   assert all(name.startswith(slug+'/') and '..' not in Path(name).parts for name in names)
   assert not any('/.git/' in n or '/tests/' in n or '/distribution/' in n or '/build/' in n for n in names)
   assert (slug+'/inc/class-kumo-hybrid-license.php' in names)==(channel=='direct')
   assert (b'Update URI:' in z.read(slug+'/style.css'))==(channel=='direct')
  manifest[channel]={'path':str(target.resolve()),'filename':target.name,'version':version,'size':target.stat().st_size,'sha256':hashlib.sha256(target.read_bytes()).hexdigest(),'files':len(names),'product_id':1189259,'variation_id':165}
(args.out/'manifest.json').write_text(json.dumps(manifest,indent=2)+'\n');print(json.dumps(manifest,indent=2))
