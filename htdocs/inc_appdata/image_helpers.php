<?php

use Intervention\Image\ImageManagerStatic as Image;

Image::configure(['driver' => 'imagick']);

class ThumbnailProcessMissingMimeType extends Exception
{
}
class ThumbnailProcessFileNotFound extends Exception
{
}

class InvalidFormat extends Exception
{
}
class PdfDoesNotExist extends Exception
{
}
class InvalidLayerMethod extends Exception
{
}
class PageDoesNotExist extends Exception
{
}

/**
 * V1.2 now adds resolution in initialization to prevent stalling system if, when initialized, second param added all calls are made with defined resolution. This is needed or system freezes when a PDF containing large mm sizes is processed.
 */
class Pdf
{
    public $imagick;
    protected $pdfFile;

    protected $resolution = 144;

    protected $outputFormat = 'jpg';

    protected $page = 1;

    protected $numberOfPages;

    protected $validOutputFormats = ['jpg', 'jpeg', 'png'];

    protected $layerMethod = Imagick::LAYERMETHOD_FLATTEN;

    protected $colorspace;

    protected $compressionQuality;

    public function __construct(string $pdfFile, int $resolution = null)
    {
        if (!file_exists($pdfFile)) {
            throw new PdfDoesNotExist("File `{$pdfFile}` does not exist");
        }

        $this->imagick = new Imagick();

        if (null !== $resolution) {
            //logfile('Class PDF: Initializing with resolution set to ' . $resolution);
            $this->setResolution($resolution);
            $this->imagick->setResolution($resolution, $resolution);
            //logfile('Class PDF: Internal resolution now reads ' . $this->resolution);
        }

        $this->imagick->pingImage($pdfFile);
        //logfile('Class PDF: pingImage complete');

        $this->numberOfPages = $this->imagick->getNumberImages();

        $this->pdfFile = $pdfFile;
    }

    public function setResolution(int $resolution)
    {
        $this->resolution = $resolution;
        //logfile('Class PDF: resolution set to ' . $resolution . ' internally. (check: ' . $this->resolution . ')');

        return $this;
    }

    public function setOutputFormat(string $outputFormat)
    {
        if (!$this->isValidOutputFormat($outputFormat)) {
            throw new InvalidFormat("Format {$outputFormat} is not supported");
        }

        $this->outputFormat = $outputFormat;

        return $this;
    }

    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    /**
     * Sets the layer method for Imagick::mergeImageLayers()
     * If int, should correspond to a predefined LAYERMETHOD constant.
     * If null, Imagick::mergeImageLayers() will not be called.
     *
     * @param null|int
     *
     * @throws InvalidLayerMethod
     *
     * @return $this
     *
     * @see https://secure.php.net/manual/en/imagick.constants.php
     * @see Pdf::getImageData()
     */
    public function setLayerMethod(?int $layerMethod)
    {
        $this->layerMethod = $layerMethod;

        return $this;
    }

    public function isValidOutputFormat(string $outputFormat): bool
    {
        return in_array($outputFormat, $this->validOutputFormats);
    }

    public function setPage(int $page)
    {
        if ($page > $this->getNumberOfPages() || $page < 1) {
            throw new PageDoesNotExist("Page {$page} does not exist");
        }

        $this->page = $page;

        return $this;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function saveImage(string $pathToImage): bool
    {
        //logfile('Class PDF: saveImage init...');
        if (is_dir($pathToImage)) {
            $pathToImage = rtrim($pathToImage, '\/') . DIRECTORY_SEPARATOR . $this->page . '.' . $this->outputFormat;
        }

        $imageData = $this->getImageData($pathToImage);
        //logfile('Class PDF: saveImage complete saving image and exiting.');

        return false !== file_put_contents($pathToImage, $imageData);
    }

    public function saveAllPagesAsImages(string $directory, string $prefix = ''): array
    {
        $numberOfPages = $this->getNumberOfPages();

        if (0 === $numberOfPages) {
            return [];
        }

        return array_map(function ($pageNumber) use ($directory, $prefix) {
            $this->setPage($pageNumber);

            $destination = "{$directory}/{$prefix}{$pageNumber}.{$this->outputFormat}";

            $this->saveImage($destination);

            return $destination;
        }, range(1, $numberOfPages));
    }

    public function getImageData(string $pathToImage): Imagick
    {
        /*
         * Reinitialize imagick because the target resolution must be set
         * before reading the actual image.
         */
        $this->imagick = new Imagick();
        //logfile('Class PDF: .. getImageData setting resolution to ' . $this->resolution);

        $this->imagick->setResolution($this->resolution, $this->resolution);

        if (null !== $this->colorspace) {
            //logfile('Class PDF: .. getImageData setting Colorspace to ' . $this->colorspace);
            $this->imagick->setColorspace($this->colorspace);
        }

        if (null !== $this->compressionQuality) {
            //logfile('Class PDF: .. getImageData setting CompressionQuality to ' . $this->compressionQuality);
            $this->imagick->setCompressionQuality($this->compressionQuality);
        }

        if (filter_var($this->pdfFile, FILTER_VALIDATE_URL)) {
            return $this->getRemoteImageData($pathToImage);
        }

        $this->imagick->readImage(sprintf('%s[%s]', $this->pdfFile, $this->page - 1));

        if (is_int($this->layerMethod)) {
            $this->imagick = $this->imagick->mergeImageLayers($this->layerMethod);
        }

        $this->imagick->setFormat($this->determineOutputFormat($pathToImage));

        return $this->imagick;
    }

    public function setColorspace(int $colorspace)
    {
        $this->colorspace = $colorspace;

        return $this;
    }

    public function setCompressionQuality(int $compressionQuality)
    {
        $this->compressionQuality = $compressionQuality;

        return $this;
    }

    protected function getRemoteImageData(string $pathToImage): Imagick
    {
        $this->imagick->readImage($this->pdfFile);

        $this->imagick->setIteratorIndex($this->page - 1);

        if (is_int($this->layerMethod)) {
            $this->imagick = $this->imagick->mergeImageLayers($this->layerMethod);
        }

        $this->imagick->setFormat($this->determineOutputFormat($pathToImage));

        return $this->imagick;
    }

    protected function determineOutputFormat(string $pathToImage): string
    {
        $outputFormat = pathinfo($pathToImage, PATHINFO_EXTENSION);

        if ('' != $this->outputFormat) {
            $outputFormat = $this->outputFormat;
        }

        $outputFormat = mb_strtolower($outputFormat);

        if (!$this->isValidOutputFormat($outputFormat)) {
            $outputFormat = 'jpg';
        }

        return $outputFormat;
    }
}

/**
 * Main thumbnailer function, throws exeptions when encountering an error.
 *
 * Usage:
 *          try {
 *               createThumbnail( $imgIn, $imgOut );
 *          } catch (Exception $e) {
 *              $error = $e->getMessage();
 *          }
 *
 * @param string $imgIn  Absolute path to source image
 * @param string $imgOut Absolute path to thumbnail file
 * @param array  $conf   Configuration object
 */
function createThumbnail($imgIn, $imgOut, $conf = [])
{
    global $home_path;

    //logfile('CT-101 init create thumbnail');
    if (!file_exists($imgIn)) {
        throw new ThumbnailProcessFileNotFound('The source file `' . $imgIn . '` cannot be found.');
    }

    // Possible override fom $conf obj
    $resize = [250, 250];

    if (isset($conf['resize']) and $conf['resize'][0] and $conf['resize'][1]) {
        $resize[0] = $conf['resize'][0];
        $resize[1] = $conf['resize'][1];
    }

    $thumbnail_format = mb_strtolower(substr($imgOut, strrpos($imgOut, '.') + 1));

    $collect_identify_data = true;
    $abort_thumbnail = false;

    // We will ignore the identify loop for PDF files as some will crash the Imagick extension
    if (str_contains($imgIn, '.')) {
        if ('pdf' == mb_strtolower(substr($imgIn, strrpos($imgIn, '.') + 1))) {
            // In any case we shall not do the Imagick identify logic below
            $collect_identify_data = false;
            $image_meta = [];
            $image_meta['mimetype'] = 'application/pdf';

            $pdf_path = dirname($imgIn);
            $pdf_name = basename($imgIn);
            if (str_contains($pdf_path, '\\')) {
                $pdf_path = dirname(str_replace('\\', '/', $imgIn));
                $pdf_name = basename(str_replace('\\', '/', $imgIn));
            }
            $pdf_meta = detect_pdfmeta_with_pdflib($pdf_path, $pdf_name);

            //$pixel_density = 8.464675892206846;

            if (null !== $pdf_meta['x']) {
                $preferred_destination_px_x = 1920;
                $imagick_dpi_to_resolution_factor = 5.342412451;
                $mm_to_inch_formula = 25.4;
                $inches = $pdf_meta['x'] / $mm_to_inch_formula;
                $dpi = $preferred_destination_px_x / $inches;
                $resolution = $dpi * $imagick_dpi_to_resolution_factor;

                $pdf_meta['resolution'] = (int) $resolution;
            } else {
                $abort_thumbnail = true;
            }

            if (!isset($pdf_meta['resolution'])) {
                $pdf_meta['resolution'] = null;
            }
        }
    }

    // Caching the identify part of the image
    if ($collect_identify_data && file_exists($imgIn . '.identify-result')) {
        //logfile('CT-102a1 cached identify data');
        $image_meta = json_decode(file_get_contents($imgIn . '.identify-result'), true);
    } elseif ($collect_identify_data) {
        //logfile('CT-102b1 exec identify from image magick, loading');
        $imagick = new Imagick($imgIn);
        $image_meta = $imagick->identifyImage();
        //logfile('CT-102b2 exec identify from image magick, saving');
        file_put_contents($imgIn . '.identify-result', json_encode($image_meta));
    } else {
        //logfile('CT-102c1 skipping identify, fuzzylogic');
    }

    //var_dump($image_meta);

    //logfile('CT-103 treating file depending on mimetype');
    if (('application/postscript' == $image_meta['mimetype']) or ('image/x-eps' == $image_meta['mimetype'])) {
        //logfile('CT-104 EPS process');
        $im = new Imagick($imgIn);
        if ('CMYK' == $image_meta['colorSpace']) {
            $im->transformImageColorspace(Imagick::COLORSPACE_SRGB);
        }
        $im->setImageFormat($thumbnail_format);
        $im->writeImage($imgOut);
        $imgx = $im->getImageWidth();
        $imgy = $im->getImageHeight();
        $im->clear();

        if ($imgx > $resize[0] or $imgy > $resize[1]) {
            $image = Image::make($imgOut)->resize($resize[0], $resize[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($imgOut);

            $imgx = $image->width();
            $imgy = $image->height();
        }
    } elseif ('application/pdf' == $image_meta['mimetype']) {
        //logfile('CT-105 PDF process');

        if (!$abort_thumbnail) {
            //logfile('CT-105 PDF process: 1/2 using Imagick to create JPG');
            if (!isset($pdf_meta)) {
                //logfile('PDF Thumbnail aborted, missing pdf meta, copying error thumb');
                copy($home_path . 'www/dist/assets/pdf-na.jpg', $imgOut);
                $imgx = $resize[0];
                $imgy = $resize[1];
            } else {
                $pdf = new Pdf($imgIn, $pdf_meta['resolution']);
                $pdf->saveImage($imgOut);
                //logfile('CT-105 PDF process: 2/2 using Image class to resize final thumb');
                $image = Image::make($imgOut)->resize($resize[0], $resize[1], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save($imgOut);
                $imgx = $image->width();
                $imgy = $image->height();
            }
        } else {
            //logfile('PDF Thumbnail aborted, copying error thumb');
            copy($home_path . 'www/dist/assets/pdf-na.jpg', $imgOut);
            $imgx = $resize[0];
            $imgy = $resize[1];
        }
    } elseif (('image/jpeg' == $image_meta['mimetype']) or ('image/x-jpeg' == $image_meta['mimetype'])
            or ('image/png' == $image_meta['mimetype']) or ('image/x-png' == $image_meta['mimetype'])
            or ('image/gif' == $image_meta['mimetype']) or ('image/x-gif' == $image_meta['mimetype']) 
            or ('image/tiff' == $image_meta['mimetype'])
            ) {
            // or ('image/x-psd' == $image_meta['mimetype']) <- why ? 
        //logfile('CT-106 Default process');
        $image = Image::make($imgIn)->resize($resize[0], $resize[1], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($imgOut);
    } else {
        throw new ThumbnailProcessMissingMimeType('Skipping creation of thumbnail, mimetype `' . $image_meta['mimetype'] . '` needs processing logic.');
    }
}

/**
 * Convert image => image
 *
 * @param string $source_image Image path
 * @param string $destination_image Image path
 * @return void
 */
function convertImage($source_image, $destination_image){
    $image = Image::make($source_image)->save($destination_image);
}


/**
 * Detect the PDF resolution in PX using PDFLIB.
 *
 * @param string $pdf_searchpath
 * @param string $pdf_filename
 *
 * @return array PDF meta: version, pages, x, y and possible error
 */
function detect_pdfmeta_with_pdflib($pdf_searchpath, $pdf_filename)
{
    //$pdf_searchpath = dirname(dirname(__FILE__)).'/data';
    //$pdf_filename = "problem.pdf";

    $pdf_meta = [
        'version' => null,
        'pages' => null,
        'x' => null,
        'y' => null,
    ];

    /*
    * The following code is taken from the starter_pcos.php file that comes with PDFLIB
    * so any info or references you will find there. This code has 1 purpose, detect the
    * width and height of the PDF file.
    */
    try {
        $p = new PDFlib();
        $p->set_option('errorpolicy=return');
        $p->set_option('stringformat=utf8');
        $p->set_option('SearchPath={{' . $pdf_searchpath . '}}');
        $doc = $p->open_pdi_document($pdf_filename, 'requiredmode=minimum');
        if (0 == $doc) {
            throw new Exception('Error: ' . $p->get_errmsg());
        }

        $pcosmode = $p->pcos_get_string($doc, 'pcosmodename');
        $pdf_meta['version'] = $p->pcos_get_string($doc, 'pdfversionstring');

        if ('minimum' == $pcosmode) {
            throw new Exception('Minimum mode: no more information available');
        }

        $pdf_meta['pages'] = $p->pcos_get_number($doc, 'length:pages');
        $pdf_meta['x'] = $p->pcos_get_number($doc, 'pages[0]/width');
        $pdf_meta['y'] = $p->pcos_get_number($doc, 'pages[0]/height');

        $p->close_pdi_document($doc);
    } catch (PDFlibException $e) {
        $pdf_meta['error'] = 'PDFlib exception occurred in starter_pcos sample:<br/>' .
            '[' . $e->get_errnum() . '] ' . $e->get_apiname() . ': ' .
            $e->get_errmsg() . '<br/>';
    } catch (Exception $e) {
        $pdf_meta['error'] = $e;
    }

    $p = 0;

    return $pdf_meta;
}

/**
 * Will check if an existing file is present, and tweak the filename until its available.
 *
 * @param string $name The filename (basename),
 * @param string $path A path (relative or absolute) for a directory the file should exist in
 * @param string $type If set the $name will have its extension replaced by type
 */
function getAvailableFilename($name, $path, $type = null)
{
    $debug = false;

    $ext = 'unknown';
    if (str_contains($name, '.')) {
        $ext = mb_strtolower(substr($name, strrpos($name, '.') + 1));
        $stub = substr($name, 0, strrpos($name, '.'));
    }

    if (null === $type) {
        $type = $ext;
    }

    if ($debug) {
        echo 'a:' . htmlentities($path) . '<br>';
    }

    // Make sure path is not relative
    if (DIRECTORY_SEPARATOR != substr($path, 0, 1)) {
        $path = realpath($path);
    }

    if ($debug) {
        echo 'b:' . htmlentities($path) . '<br>';
    }

    // Make sure path doesnt end with slash
    if (DIRECTORY_SEPARATOR == substr($path, -1)) {
        $path = substr($path, 0, -1);
    }

    if ($debug) {
        echo 'c:' . htmlentities($path) . '<br>';
    }

    $availName = false;

    if ($debug) {
        echo 'PROBING FOR POSSIBLE FILENAME AVAILABILITY' . '<br>';
    }
    for ($x = 0; $x <= 10; ++$x) {
        if ($x) {
            $file_pattern = $stub . '_' . $x . '.' . $type;
        } else {
            $file_pattern = $stub . '.' . $type;
        }

        if ($debug) {
            echo htmlentities(' -> ' . $path . DIRECTORY_SEPARATOR . $file_pattern);
        }
        if (!file_exists($path . DIRECTORY_SEPARATOR . $file_pattern)) {
            $availName = $file_pattern;

            break;
        }
        if ($debug) {
            echo ' - file already exists';
        }

        if ($debug) {
            echo '<br>';
        }
    }

    return $availName;
}

/**
 * Perform a thumbnail create supporting all formats.
 *
 * @param string $imgIn   Path to image file of some format
 * @param mixed  $cleanUp
 *
 * @return string On success returns path with the .jpg file, else returns $imgIn
 */
function getPath_convertToJpeg($imgIn, $cleanUp = true)
{
    if (!file_exists($imgIn)) {
        return false;
    }

    $meta = pathinfo($imgIn);
    $newFileName = getAvailableFilename($meta['basename'], $meta['dirname'], 'jpg');
    $imgOut = $meta['dirname'] . DIRECTORY_SEPARATOR . $newFileName;

    try {
        createThumbnail(
            $imgIn,
            $imgOut,
            ['resize' => [500, 500]]
        );
        $status = 'success';
    } catch (Exception $e) {
        $status = 'failed';
    }

    if ('success' == $status) {
        if ($cleanUp) {
            @unlink($imgIn);
        }

        return $imgOut;
    }
    if ($cleanUp) {
        @unlink($imgOut);
    }

    return $imgIn;
}

/**
 * When saving uplooaded files to disk this function makes sure that there are no
 * spaces or special characters that could create problems down the road. Typically
 * all UTF8 characters are removed and internationalized characters are replaced with
 * corresponding characters, fuzzy logic style.
 *
 * @param string $string The string that needs to be ASCHIIfied
 * @param string $space  The character considered space in tyhe string, for files use hyphen
 *
 * @return ASCHII'fied string
 */
function filename_aschiify($string, $space = ' ')
{
    $table = [
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z',
        'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
        'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
        'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
        'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
        'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
        'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
    ];
    $string = trim($string);
    $string = strtr($string, $table);

    $utf8 = [
        '/–/' => '-',
        '/[’‘‚‛❛❜❟]/u' => '\'',
        '/[❮❯‹›<>[\]]/u' => '\'',
        '/[‟“”«»„❝❞⹂⹂〝〞〝〟＂]/u' => '"',
    ];
    $string = preg_replace(array_keys($utf8), array_values($utf8), $string);
    //$string = str_replace(['"',"'"], [' ',' '], $string);
    $string = trim(preg_replace('/[\\s]+/', ' ', $string));

    if (' ' !== $space) {
        $string = str_replace(' ', $space, $string);
    }

    return $string;
}


/**
 * Return a formatted size in a logical unit from bytesize
 *
 * @param int $bytes The size in bytes
 * 
 * @return void
 */
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}
function show_kb($bytes){
    return formatSizeUnits($bytes);
}

/**
 * Split a filename with extension into two parts
 *
 * @param string $filename The filename to split
 *
 * @return array An array having name and ext for the filename
 */
function splitFilename($filename){
  $t_verEXT = explode('.',$filename);
  $t_EXT = '.' . $t_verEXT[count($t_verEXT)-1];
  $t_FILE = '';
  if(count($t_verEXT)>=3){
    $parts = [];
    for($i=0;$i<count($t_verEXT) - 1;$i++){
      $parts[] = $t_verEXT[$i];
    }
    $t_FILE = implode('.', $parts);
  } else {
    $t_FILE .= $t_verEXT[0];
  }
  return ['name'=>$t_FILE, 'ext'=>$t_EXT];
}

/**
 * imageToBase64 - OpenAI helper
 *
 * @param string $image_path Disk filepath
 * @param boolean $add_pre_markup 
 * @return void
 */
function imageToBase64($image_path, $add_pre_markup=true) {

    // Check if the file exists
    if (!file_exists($image_path)) {
        return false;
    }
    
    // Get the file extension
    $extension = pathinfo($image_path, PATHINFO_EXTENSION);
    
    // Read the image file contents
    $image_data = file_get_contents($image_path);
    
    // Convert the image data to Base64 encoding
    if( $add_pre_markup )
        $base64_image = 'data:image/' . $extension . ';base64,' . base64_encode($image_data);
        else
        $base64_image = base64_encode($image_data);

    // Return the Base64 encoded image
    return $base64_image;
}