# Free updates through FluentCart

The source theme remains independent of a licensing service. `distribution/build.py` creates clean and direct-download packages. The direct package adds the same free-license updater model used by the Stackless theme.

The product is Kumo Hybrid on https://gauravtiwari.org/product/kumo-hybrid/ (product 1189259, variation 165). The checkout is free, with a lifetime key and unlimited site activations. Appearance > Kumo Hybrid License accepts the key. All theme features work without a key; the key enables updates only.

The direct package adds `Update URI: https://gauravtiwari.org/product/kumo-hybrid/#license`, injects `inc/class-kumo-hybrid-license.php`, and boots `Kumo_Hybrid_License`. License requests send the key or activation hash, theme version, and website address to GauravTiwari.org. Nothing is sent before activation except when an administrator uses the update settings. WordPress's own automatic-update preference determines installation.

Build both packages with:

```sh
python3 distribution/build.py --channel all
```

The manifest records size and SHA-256 for each ZIP. The clean package has no license client and is not represented as a WordPress.org-listed release. Customer packages use FluentCart's R2 storage and license-version endpoint. Never replace an existing R2 artifact under the same versioned object key. Set the FluentCart download row and `license_settings.global_update_file` only after the stored artifact passes integrity checks.
