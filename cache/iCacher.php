<?php

    /**
     * @author  Rpsl ( 2010 )   < im.vitman@gmail.com >
     * @link    http://github.com/Rpsl/iCacher
     * @link    http://blog.rpsl.info
     * @version 0.6
     */

    /**
     * Скрипт iCacher создан что бы облечить кеширование изображений на сайте
     * и организовать лаконичную возможность генерации картинок различных размеров.
     * iCacher является т.н. роутером для http://phpthumb.gxdlabs.com/ и не будет
     * работать правильно при отсутвие данной библиотеки.
     *
     * Для правильно работы подразумевается соблюдение нескольких правил:
     *
     *  1. В папке MAIN_FOLDER хранятся оригинальные изображения.
     *  2. В папку CACHE_FOLDER будут храниться измененные изображения.
     *  3. При обращение к несуществующему файлу из папки CACHE_FOLDER происходит
     *      перенаправление на данный файл*, который в свою очередь генерирует картинку
     *      нужного размера либо возвращает 404 ошибку.
     *
     *      Это необходимо, что-бы после генерации картикнки, на повторный запрос отдавался статический файл.
     *
     *          * .htaccess rewrite rule:
     *              RewriteRule   ^images_folder/([0-9a-z]+)/([0-9a-z]+)/(.*)$  iCacher.php?param=$1&size=$2&file=$3 [L,QSA]
     *
     *  4. После генерации изображений они должны быть доступны как статичный файл.
     *  5. Для обновления миниатюр вы должны самостоятельно организовать удаление
     *      созданых скриптом файлов.
     *      В крайнем случае можно использовать GET параметр flush с любым значением.
     *
     *
     *  При необходимости создавайте собственные плагины или ф-ции обработки.
     */

    error_reporting( ~E_ALL );
    ini_set( 'display_errors', 0 );
    

    
    ini_set("memory_limit", "1024M");

    /**
     *  Папка с оригинальными изображениями
     */
    define( 'MAIN_FOLDER', realpath(dirname(__FILE__).'/../sitefiles') );

    /**
     * CACHE_FOLDER в большинстве случаев является простым алиасом для MAIN_FOLDER,
     * но может быть полезна, если кеш картинок у вас лежит в не стандартной папке,
     * например вынесен на отдельный домен и отдается другим web сервером.
     *
     * Будьте бдительны, если после генерации миниатюр они не будут доступны по
     * URL который используется первый раз, то они не будут кешироваться на
     * стороне клиента.
     */
    
     
    define( 'CACHE_FOLDER',     MAIN_FOLDER );

    /**
     * Укажите путь к папке phpthumb
     */
    define( 'PHPTHUMB_FOLDER', realpath(dirname(__FILE__)) );

    /**
     * Качество создаваемых изображений.
     */
    define( 'JPEG_QUALITY', 80);

    /* Paranoic mode */
   // $_GET['file'] = basename( $_GET['file'] );
    $_GET['size'] = preg_replace( '/[^x0-9]/', '', $_GET['size'] );

    $image = CACHE_FOLDER . '/' . $_GET['param'] . '/' . $_GET['size'] . '/' . $_GET['file'];

    
    // На всякий случай, в идеале мы не должны попадать в эту ветку.
    if( file_exists( $image ) AND !isset( $_GET['flush'] ) )
    {
        $file_info = getimagesize( $image );
        header( 'Content-type: ' . $file_info ['mime'] );
        echo file_get_contents( $image );
        die();
    }

    unset( $image );

    if( !empty( $_GET['file'] )
        AND !empty( $_GET['size'] )
        AND !empty( $_GET['param'] )
        AND file_exists(realpath( MAIN_FOLDER . '/' . $_GET['file'] ))
    )
    {

        if( !is_numeric( $_GET['size'] ) )
        {
            $size = explode( 'x', $_GET['size'] );
        }
        else
        {
            $size[0] = $_GET['size'];
            $size[1] = 0;
        }

        try
        {
            require_once PHPTHUMB_FOLDER . '/ThumbLib.inc.php';

            $options = array(
                'resizeUp'              => true,
                'jpegQuality'           => JPEG_QUALITY,
                'correctPermissions'    => true
            );
            
            $fileWMarks = array(
                1 => '',
                2 => '/watermarks/water_pattern.png',
            );


            $T = PhpThumbFactory::create( MAIN_FOLDER . '/' . $_GET['file'] , $options);

            switch( $_GET['param'] ):
                case 'rn':
                    /**
                     * Обычный ресайз
                     */
                    $file = ResizeNormal( $T, $_GET['file'], $size );
                break;
                case 'rnwm':
                    /**
                     * Обычный ресайз
                     */
                    $file = ResizeNormalWm( $T, $_GET['file'], $size, $fileWMarks);
                break;

                case 'rl':
                    /**
                     * Ресайз по большей стороне
                     */
                    $file = ResizeLargeSide( $T, $_GET['file'], $size );
                break;

                case 'ra':
                    /**
                     * Адаптивный ресайз
                     */
                    $file = ResizeAdaptive( $T, $_GET['file'], $size );
                break;

                case 'cc':
                    /**
                     * Обрезание картинки от центра
                     */
                    $file = CropFromCenter( $T, $_GET['file'], $size );
                break;

                case 'rc':
                    /**
                     * Скругленные углы
                     */
                    $file = RoundedCorners( $T, $_GET['file'], $size, 10, 10 );
                break;

                case 'fx':
                    /**
                     * Фиксированный ресайз
                     */
                    $file = FixedResize( $T, $_GET['file'], $size );
                break;
            
                case 'rawm':
                    /**
                     * Адаптивный ресайз + водяной знак
                     */
                    $file = ResizeAdaptiveWm( $T, $_GET['file'], $size, $fileWMarks);
                break;

                default:
                    header("HTTP/1.0 404 Not Found");
                    die();
                break;
            endswitch;

            SaveFile( $T, $file );
        }
        catch( Exception $e )
        {
            header("HTTP/1.0 404 Not Found");
            die();
        }
    }
    else
    {
        header("HTTP/1.0 404 Not Found");
        die();
    }

    /**
     * Обычный ресайз
     *
     * Стандартное изменение размера картинки, по двум сторонам
     * или только по ширине, если параметр $size[1] не будет задан.
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     * @param   string    $folder
     *
     * @return  string    $file_path
     */
    function ResizeNormal( $t , $file, $size, $folder = 'rn' )
    {
        
        $imgsize = getimagesize(MAIN_FOLDER.'/'.$file);

        if( is_array( $size ) )
        {	

	        if ($imgsize[0] < $size[0] && ( $imgsize[1] < $size[1] || $size[1] == 0))
	        {
		         $t->resize( $imgsize[0], $imgsize[1] );
	        }
	         else
	        {
		         $t->resize( $size[0], $size[1] );
	        }

        }
         else
        {	
	        if ($imgsize[0] < $size)
	        {

		         $t->resize( $imgsize[0] );
	        }
	         else
	        {
		         $t->resize( $size );
	        }
           
        }


        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }
    
    /**
     * Обычный ресайз + водяной знак
     *
     * Стандартное изменение размера картинки, по двум сторонам
     * или только по ширине, если параметр $size[1] не будет задан.
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     * @param   string    $folder
     * @param   array     $filesWm
     *
     * @return  string    $file_path
     */
    function ResizeNormalWm( $t , $file, $size, $filesWm, $folder = 'rnwm' )
    {
        if( is_array( $size ) )
        {
            $t->resize( $size[0], $size[1] );
        }
        else
        {
            $t->resize( $size );
        }

        $t->createWatermark(PHPTHUMB_FOLDER . $filesWm[2], 0, 0);
        
        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }

    /**
     * Ресайз по большей стороне
     *
     * Тоже самое что и обычный ресайз, но параметр $size[1]
     * не учитывается.
     * Будут определены размеры исходного файла и в зависимости от того,
     * какая сторона больше - она будет ужата до $size[0]
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     *
     * @return  string    $file_path
     */
    function ResizeLargeSide( $t , $file, $size )
    {
        list( $size_ori[0], $size_ori[1] ) = getimagesize( MAIN_FOLDER . '/' . $file);

        $vw = 0;
        if ($size_ori[0] > $size_ori[1])
        {
	        $vw = 0;
        }
         else
        {
	        $vw = 1;
        }
        
        if ($size_ori[$vw] < $size[0])
        {
	        $size = $size_ori;
        }
        



        $key = max_key( $size_ori );
 
        if( $key == 0 )
        {
            // Если ширина больше высоты
            $size = $size[0];
        }
        else
        {
            // Если высота больше ширины
            $size = array( 0, $size[1] );
        }

        $file = ResizeNormal( $t, $file, $size, 'rl' );

        return $file;
    }

    /**
    * Адаптивный ресайз + водяной знак
    *
    * Всегда возвращает картинку заданных размеров.
    * Работает хитро, но очень круто.
    *
    * @param    Object    $T (phpThumb)
    * @param    string    $file
    * @param    mixed     $size
    * @param    string    $folder
    * @param   array     $filesWm
    *
    * @return  string    $file_path
    */
    function ResizeAdaptiveWm( $t, $file, $size, $filesWm, $folder = 'rawm' )
    {
        $t->adaptiveResize( $size[0], $size[1] );

        $t->createWatermark(PHPTHUMB_FOLDER . $filesWm[2], 0, 0);
       
        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }
    
    /**
    * Адаптивный ресайз
    *
    * Всегда возвращает картинку заданных размеров.
    * Работает хитро, но очень круто.
    *
    * @param    Object    $T (phpThumb)
    * @param    string    $file
    * @param    mixed     $size
    * @param    string    $folder
    *
    * @return  string    $file_path
    */
    function ResizeAdaptive( $t, $file, $size, $folder = 'ra' )
    {
        $t->adaptiveResize( $size[0], $size[1] );

        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }

    /**
     * Обрезание картинки от центра
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     * @param   string    $folder
     *
     * @return  string    $file_path
     */
    function CropFromCenter( $t, $file, $size, $folder = 'cc' )
    {
        if( empty( $size[0] ) OR empty( $size[1] ) )
        {
            $size[ min_key( $size ) ] = $size[ max_key( $size ) ];
        }

        $t->CropFromCenter( $size[0], $size[1] );

        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;

    }

    /**
     * Скругленные углы
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     * @param   int       $radius
     * @param   int       $rate
     *
     * @return  string    $file_path
     */
    function RoundedCorners( $t, $file, $size, $folder = 'rc', $radius = 10, $rate = 10 )
    {
        $t->adaptiveResize( $size[0], $size[1] )->createRounded( $radius, $rate );

        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }

    /**
     * Фиксированный ресайз
     *
     * Изменение размера картинки в фиксированный размер с задником, по двум сторонам
     * или только по ширине, если параметр $size[1] не будет задан.
     *
     * @param   Object    $T (phpThumb)
     * @param   string    $file
     * @param   mixed     $size
     * @param   string    $color
     * @param   string    $folder
     *
     * @return  string    $file_path
     */
    function FixedResize( $t, $file, $size, $color = '#FFFFFF', $folder = 'fx' )
    {
    

        if( is_array( $size ) )
        {
            $t->resizeFixedSize( $size[0], $size[1], $color );
        }
        else
        {
            $t->resizeFixedSize( $size, $size, $color );
        }

        $save_size = SaveSize( $size );

        $file = CACHE_FOLDER . '/' . $folder . '/'. $save_size . '/' . $file;

        return $file;
    }

    /**
     * Сохранение сгенерированной картинки
     *
     * @param Object    $T (phpthumb)
     * @param string    $filepath
     */
    function SaveFile( $T, $file )
    {
        if( is_object( $T ) AND !empty( $file ) )
        {
            extract( pathinfo( $file ), EXTR_PREFIX_SAME, "");

            if( !is_dir( $dirname ) )
            {
                mkdir( $dirname, 0777, true);
                chmod( $dirname , 0777 );
            }

			$T->save( $file, 'png' );
            
			if (empty($_GET['no_water'])) {
				
	            $image = $file;
	
	            $img   = getimagesize( $file );

	            switch ($img['mime']) {
	            	
	            	case 'image/gif':
	            		$new_img_id = imagecreatefromgif( $file );
	            	break;
	            	
	            	case 'image/jpeg':
	            		$new_img_id = imagecreatefromjpeg( $file );
	            	break;
	            	
	            	case 'image/png':
	            		$new_img_id = imagecreatefrompng( $file );
	            	break;

                    case 'image/bmp':
                        $new_img_id = imagecreatefromwbmp( $file );
                        break;

                    case 'image/x-windows-bmp':
                        $new_img_id = imagecreatefromwbmp( $file );
                        break;
	            		
	            }
	
	
	            $size  = $img[0];
	            $size2 = $img[1];
	             
				$watermark = imagecreatefrompng('copy.png');
				$padding_x = (137*2)+7;
				$padding_y = 27*2;
	            
	            
				$quality = 71;
	
				
				$watermarkwidth  = imagesx($watermark);
				$watermarkheight = imagesy($watermark);
				$startwidth 	 = ($size/2 - ($padding_x/2));
				$startheight 	 = ($size2/2 - $padding_y/2);
		
				imagecopy($new_img_id, $watermark,  $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
	
	            imagepng($new_img_id, $file);
	 
	            
			    if( file_exists( $file ) AND !isset( $_GET['flush'] ) )
			    {
			        $file_info = getimagesize( $file );
			        header( 'Content-type: ' . $file_info ['mime'] );
			
			        echo file_get_contents( $file );
			        die();
			    }
	
	            $T->oldImage = $new_img_id;
            
			}
             
            $T->show();

        }
    }

    /**
     * Генерация пути размера
     *
     * @param mixed $size
     * @return sting $save_size
     */
    function SaveSize( $size )
    {
        if( is_array( $size ) )
        {
            if( !empty( $size[0] ) AND !empty( $size[1] ) )
            {
                $save_size = $size[0] . 'x' . $size[1] ;
            }
            elseif( !empty( $size[0] ) AND empty( $size[1] ) )
            {
                $save_size = $size[0];
            }
            elseif( empty( $size[0] ) AND !empty( $size[1] ) )
            {
                $save_size = $size[1];
            }
        }
        else
        {
            $save_size = $size;
        }

        return $save_size;
    }


    // ----- Вспомогательные функции -----------

    /**
     * Возвращает ключ элемента с максмальным значением
     *
     * @param   mixed $array
     * @return  sting $key
     */
    function max_key ( $array )
    {
        if( is_array( $array ) ) { foreach ( $array as $key => $val ) { if ( $val == max( $array ) ) { return $key; } } }
    }

    /**
     * Возвращает ключ элемента с минимальным значением
     *
     * @param   mixed $array
     * @return  sting $key
     */
    function min_key ( $array )
    {
        if( is_array( $array ) ) { foreach ( $array as $key => $val ) { if ( $val == min( $array ) ) { return $key; } } }
    }
