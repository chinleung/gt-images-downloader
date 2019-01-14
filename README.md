# GT Images Downloader

Download the optimized images from GT Metrix recommendations automatically.

## Usage

1. Copy the **download.php** into the root of your project.
2. Execute the script with your domain and the url of the GT Metrix report.

```
php download.php --url=https://gtmetrix.com/reports/example.com/ABC4xDEF --domain=https://example.com
```

The script will download all the images from the list of recommendations in the report that matches the domain provided in the option.
