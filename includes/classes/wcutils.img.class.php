<?php

/**
 * WaterCooler Chat (Image Utility class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcImg {

    /**
     * Initializes an image resource
     * 
     * @since 1.4
     * @param string $original_image Image URL Address
     * @return resource|bool
     */
    public static function initImg($original_image) {

        $source_image = '';
        if(!function_exists('gd_info')) { return FALSE; }

        // Try to use the faster exif_imagetype function if it exists
        if(function_exists('exif_imagetype')) {
            $type = exif_imagetype($original_image);
        } else {
            $tmp = @getimagesize($original_image);
            $type = $tmp[2];
        }

        // Create the image resource
        if($type == 1) {
            $source_image = @ImageCreateFromGIF($original_image);
        }

        if($type == 2) {
            $source_image = @ImageCreateFromJPEG($original_image);
        }

        if($type == 3) {
            $source_image = @ImageCreateFromPNG($original_image);
        }

        if($source_image) {
            return $source_image;
        } else {
            return FALSE;
        }
    }

    /**
     * Corverts simple BBCODE image tags to enhanced tags with the image details, generates thumbnails if necessary/possible
     * 
     * @since 1.1
     * @param string $s User Post
     * @param string|null $attach Specifies If It Must Be Treated As An Attachment
     * @return string
     */
    public static function parseImg($s, $attach = NULL) {

        $source = (is_array($s) ? $s[1] : $s);
        $source_tag = (
            ($attach === NULL) ? 
            $source : 
            str_replace(WcChat::$includeDirServer . 'files/attachments/', '', $source)
        );

        // Use http instead of https (in case https wrapper is not installed)
        $iname = str_replace('https://', 'http://', $source);
        $image = self::initImg($iname);
        
        $target = 'files/thumb/tn_' . strtoupper(dechex(crc32($iname))) . '.jpg';

        if($image) {
            $w = imagesx($image);
            $h = imagesy($image);
        } else {
            $w = $h = 0;
        }

        $nw = $nh = 0;

        // Generate image tags containing the dimensions
        if($w && $h) {
            if($h > IMAGE_MAX_DSP_DIM || $w > IMAGE_MAX_DSP_DIM) {
                if($w >= $h) {
                    $nh = intval(($h * IMAGE_MAX_DSP_DIM) / $w);
                    $nw = IMAGE_MAX_DSP_DIM;
                } else {
                    $nw = intval(($w * IMAGE_MAX_DSP_DIM) / $h);
                    $nh = IMAGE_MAX_DSP_DIM;
                }

                if(GEN_REM_THUMB || $attach !== NULL) {
                    if(
                        self::thumbnailCreateMed(
                            $image,
                            $w,
                            $h, 
                            WcChat::$includeDirServer . $target,
                            IMAGE_MAX_DSP_DIM
                        )
                    ) {
                        return 
                            '[IMG' . 
                                ($attach !== NULL ? 'A' : '') . '|' . 
                                $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                                '|tn_' . strtoupper(dechex(crc32($iname))) . 
                            '|]' . 
                                $source_tag . 
                            '[/IMG]'
                        ;
                    } else {
                        return 
                            '[IMG' . 
                                ($attach !== NULL ? 'A' : '') . '|' . 
                                $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                            '|]' . 
                                $source_tag . 
                            '[/IMG]';
                    }
                } else {
                    return 
                        '[IMG' . 
                            ($attach !== NULL ? 'A' : '') . '|' . 
                            $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                        '|]' . 
                            $source_tag . 
                        '[/IMG]';
                }
            } else {
                return '[IMG' . 
                        ($attach !== NULL ? 'A' : '') . '|' . 
                        $w . 'x' . $h . '|' . $w . 'x' . $h . 
                        '|]' . 
                        $source_tag . 
                '[/IMG]';
            }
        } else {
                return '[IMG|' . IMAGE_AUTO_RESIZE_UNKN . '|]' . $source . '[/IMG]';
        }
    }
    
    /**
     * Generates thumbnails for youtube videos
     * 
     * @since 1.4
     * @param string $s Video url parameters
     * @return string
     */
    public static function parseVideoImg($s) {
        $image = self::initImg('https://img.youtube.com/vi/'.$s[1].'/0.jpg');
        
        if($image) {
            $w = imagesx($image);
            $h = imagesy($image);
            
            $target = 'files/thumb/tn_youtube_' . $s[1] . '.jpg';
            
            if(
                $w > 0 && $h > 0
            ) {
                if(
                    self::thumbnailCreateMed(
                        $image,
                        $w,
                        $h, 
                        WcChat::$includeDirServer . $target,
                        IMAGE_MAX_DSP_DIM
                    )
                ) {
                    return '[YOUTUBE]'.$s[1].'[/YOUTUBE]';
                }
            }
        }
        return $s[0];
    }

    /**
     * Generates a normal thumbnail
     * 
     * @since 1.3
     * @param string $original_image Path
     * @param resource $source_image
     * @param int $w Image Width
     * @param int $h Image Height
     * @param string $target_image
     * @param int $thumbsize
     * @return bool
     */
    public static function thumbnailCreateMed (
        $source_image, $w, $h, $target_image,
        $thumbsize
    )
    {
        if(!$source_image || !function_exists('gd_info')) { return FALSE; }

        // If at least one dimension is bigger than thumbnail, calculate resized dimensions
        if($w > $thumbsize OR $h > $thumbsize) {
            if($w >= $h) {

                $sizey = $thumbsize * $h;
                $thumbsizey = intval($sizey / $w);
                $temp_image = @ImageCreateTrueColor($thumbsize, $thumbsizey);
                $thw = $thumbsize; $thy = $thumbsizey;
            } else {
                $sizew = $thumbsize * $w;
                $thumbsizew = intval($sizew / $h);
                $temp_image = @ImageCreateTrueColor($thumbsizew, $thumbsize);
                $thw = $thumbsizew; $thy = $thumbsize;
            }
        } else {
            $thw = $w; $thy = $h;
            $temp_image = @ImageCreateTrueColor($thw, $thy);
        }

        @ImageCopyResampled(
            $temp_image, $source_image, 
            0, 0, 0, 0, 
            $thw, $thy, $w, $h
        );

        @ImageJPEG($temp_image, $target_image, 80);

        @ImageDestroy($temp_image);

        if (!file_exists($target_image)) { return false; } else { return true; }
    }

    /**
     * Generates a cropped square thumbnail
     * 
     * @since 1.1
     * @param string $original_image Path
     * @param string $target_image
     * @param int $thumbsize
     * @return bool
     */
    public static function thumbnailCreateCr ($original_image, $target_image, $thumbsize) {

        $source_image = self::initImg($original_image);

        if(!$source_image || !function_exists('gd_info')) { return FALSE; }

        $w = imagesx($source_image);
        $h = imagesx($source_image);

        // Set offset for the picture cropping (depending on orientation)
        if ($w > $h) {
            $smallestdimension = $h;
            $widthoffset = ceil(($w - $h) / 2);
            $heightoffset = 0;
        } else {
            $smallestdimension = $w;
            $widthoffset = 0;
            $heightoffset = ceil(($h - $w) / 2);
        }

        // Create a temporary image for cropping original (using smallest side for dimensions)
        $temp_image1 = @ImageCreateTrueColor($smallestdimension, $smallestdimension);
        // Resize the image to smallest dimension (centered using offset values from above)
        @ImageCopyResampled(
            $temp_image1, $source_image, 0, 0, 
            $widthoffset, $heightoffset, $smallestdimension, $smallestdimension, 
            $smallestdimension, $smallestdimension
        );
        // Create thumbnail and save

        @ImageJPEG($temp_image1, $target_image, 90);

        // Create a temporary new image for the final thumbnail
        $temp_image2 = @ImageCreateTrueColor($thumbsize, $thumbsize);
        // Resize this image to the given thumbnail size
        @ImageCopyResampled(
            $temp_image2, $temp_image1, 0, 0, 0, 0, 
            $thumbsize, $thumbsize, $smallestdimension, $smallestdimension
        );
        // Create thumbnail and save

        @ImageJPEG($temp_image2, $target_image, 90);

        // Delete temporary images
        @ImageDestroy($temp_image1);
        @ImageDestroy($temp_image2);
        // Return status (check for file existance)
        if (!file_exists($target_image)) { return false; } else { return true; }
    }
}


?>