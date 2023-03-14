<?php	
   // Show Starred Files
    $msg='';
	$sucessmsg='';
    if(isset($_GET['starreddel'])){
		$result=$con->query("DELETE from filemanager_starred WHERE Id=".$_GET['starreddel']);
	    $sucessmsg="starred";
	}
	//if (isset($_GET['starred']) && !FM_READONLY) {
		$result=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']);
	    $result_file=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']." AND file_type='file'"); 
	    $row_file_cnt = $result_file->num_rows;
		
		$result_folder=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']." AND file_type='folder'"); 
	    $row_folder_cnt = $result_folder->num_rows;
	?>
	  <div class="d-flex flex-wrap">
            <h5 class="me-3">Starred Files</h5>
                <div class="ms-auto">
                    <a href="javascript: void(0);" class="font-size-18">View All</a>
                </div>
        </div>
		
		   <form action="" method="post" class="w-100">
                <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                <input type="hidden" name="group" value="1">
                <div class="m-table-responsive">
                    <table class="table table-hover table-sm <?php echo $tableTheme; ?>" id="main-table2">
                        <thead class="thead-white">
                        <tr>
                            <?php if (!FM_READONLY): ?>
                                <th style="width:3%" class="custom-checkbox-header">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="js-select-all-items" onclick="checkbox_toggle()">
                                        <label class="custom-control-label" for="js-select-all-items"></label>
                                    </div>
                                </th><?php endif; ?>
                            <th><?php echo lng('Name') ?></th>
                            <th><?php echo lng('Size') ?></th>
                            <th><?php echo lng('Modified') ?></th>
                            <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                <th><?php// echo lng('Perms') ?></th>
                                <th><?php// echo lng('Owner') ?></th><?php endif;  ?>
                            <th><?php echo lng('Actions') ?></th>
                        </tr>
                        </thead>
                        <?php
                        // link to parent folder
                        if ($parent !== false) {
                            ?>
                            <tr><?php if (!FM_READONLY): ?>
                                <td class="nosort"><a href="?action=filemanager&p=<?php echo urlencode($parent) ?>"><i class="fa fa-chevron-circle-left go-back"></i></a></td><?php endif; ?>
                                <td data-sort></td>
                                <td data-order></td>
                                <td data-order></td>
                                <td></td>
                                <?php if (!FM_IS_WIN && !$hide_Cols) { ?>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        $istar = 33999;
						while($data=mysqli_fetch_assoc($result)){
						  if($data['file_type']  == 'folder'){
								$is_link = is_link($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') .'/'.$data['file_name']);
								$img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
								$modif_raw = filemtime($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : ''). '/' . $data['file_name']);
								$modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            if ($calc_folder) {
                                $filesize_raw = fm_get_directorysize(fm_get_size($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') .'/'.$data['file_name']));
                                $filesize = fm_get_filesize($filesize_raw);
                            }
                            else {
                                $filesize_raw = "";
                                $filesize = lng('Folder');
                            }
                            $perms = substr(decoct(fileperms($path . '/' . $data['file_name'])), -4);
                            if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                $group = posix_getgrgid(filegroup($path . '/' . $f));
                            } else {
                                $owner = array('name' => '?');
                                $group = array('name' => '?');
                            }
                            ?>
                            <tr id="<?php echo $istar ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="<?php echo $istar ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                                            <label class="custom-control-label" for="<?php echo $istar ?>"></label>
                                        </div>
                                    </td><?php endif; ?>
                                    <td data-sort=<?php echo fm_convert_win(fm_enc($f)) ?>>
                                        <div class="filename"><a href="?action=filemanager&p=<?php echo urlencode(trim(($data['root_path'] != '' ? '/' . $data['root_path'] : '') . '/' . $data['file_name'], '/')) ?>"><span class="thumb"><i class="<?php echo $img ?>"></i></span> <?php echo fm_convert_win(fm_enc($data['file_name'])) ?>
                                            </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                                    </td>
                                    <td data-order="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT);?>">
                                        <?php echo $filesize; ?>
                                    </td>
                                    <td data-order="a-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                                <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                    <td>
                                    </td>
                                <td></td>
                                <?php endif;  ?>
                                <td class="inline-actions">
                                    <div class="dropdown">
                                            <a class="font-size-16 text-muted" href="#" role="button" id="substarreddropdownMenuLink"
                                                data-mdb-toggle="dropdown" aria-expanded="false" > <i class="fa fa-ellipsis-h"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="substarreddropdownMenuLink">
                                                <li> <a class="dropdown-item" title="<?php echo lng('DirectLink')?>" href="?action=filemanager&p=<?php echo urlencode(trim(($data['root_path'] != '' ? '/' . $data['root_path'] : '') . '/' . $data['file_name'], '/')) ?>" target="_blank"><?php echo lng('DirectLink')?></a><li>
                                            <?php if (!FM_READONLY): ?>
                                                <!--<li><a class="dropdown-item" title="<?php echo lng('Rename')?>" href="#" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename')?></a></li>
                                                <li><a class="dropdown-item" title="<?php echo lng('CopyTo')?>..." href="?action=filemanager&p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('CopyTo')?></a></li>-->
                                                <!--<li><a  class="dropdown-item" title="<?php echo lng('Starred')?>" href="#" onclick="checkit('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a><!--<a class="dropdown-item" title="<?php echo lng('Starred')?>..." href="?action=filemanager&p=&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred')?></a></li>-->
												<li> <a class="dropdown-item" title="<?php echo lng('Unstarred') ?>" href="?action=filemanager&p=&amp;starred&amp;starreddel=<?php echo $data['Id'] ?>" onclick="return confirm('<?php echo lng('Unstarred').' '.lng('File').'?'; ?>\n \n ( <?php echo urlencode($data['file_name']) ?> )');"> <?php echo lng('Unstarred') ?></a></li>
                                                <li><hr class="dropdown-divider" /></li>
												<li><a class="dropdown-item" href="#" onclick="sendtotrash('<?php echo urlencode(trim($data['root_path'] . '/' . $data['file_name'], '/')) ?>', <?php echo $istar ?>)"><span>Delete</span></a></li> 
											<?php endif; ?>
                                           </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            }
							else {
                            $is_link = is_link($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') .'/'.$data['file_name']);
							$img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $data['file_name']);
							$modif_raw = filemtime($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') .'/'.$data['file_name']);
                            $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            $filesize_raw = fm_get_size($path . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') .'/'.$data['file_name']);
                            $filesize = fm_get_filesize($filesize_raw);
							$filelink = '?action=filemanager&p=' . urlencode($data['root_path']) . '&amp;view=' . urlencode($data['file_name']);	
							   ?>
								<tr id="<?php echo $istar ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                                        <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                                    </div>
                                    </td><?php endif; ?>
                                <td data-sort=<?php echo fm_enc($f) ?>>
                                    <div class="filename">
                                    <?php
                                    if (in_array(strtolower(pathinfo($data['file_name'], PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                             <?php $imagePreview = fm_enc(FM_IMAGE_URL.($data['root_path'] != '' ? '/' . $data['root_path'] : '').'/'.$data['file_name']); ?>
                                        <a href="#" onclick="starred_preview_show('<?php echo $istar; ?>')" data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fm_enc($data['file_name']) ?>"><span onclick="starred_preview_show('<?php echo $istar; ?>')"><img src="<?php echo $imagePreview ?>" alt="img"></span> <?php  echo fm_convert_win(fm_enc($data['file_name']))?>
									<?php else: ?>
                                        <a href="#" onclick="starred_preview_show('<?php echo $istar; ?>')"><span onclick="starred_preview_show('<?php echo $istar; ?>')" ><i class="<?php echo $img ?>" style="font-size: 48px; padding-left: 12px;"></i></span><?php echo fm_convert_win(fm_enc($data['file_name'])) ?>
									<?php endif; ?>
                                        </span></a>
                                        <?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?>
                                    </div>
                                </td>
                                <td data-order="b-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>"><span title="<?php printf('%s bytes', $filesize_raw) ?>">
                                    <?php echo $filesize; ?>
                                    </span></td>
                                <td data-order="b-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                                <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                    <td>
                                    </td>
                                    <td></td>
                                <?php endif;  ?>
                                <td class="inline-actions">
                                <div class="dropdown">
                                    <a class="font-size-16 text-muted" href="#" role="button" id="starredDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false" > <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="starredDropdownMenuLink">
                                        <li> <a class="dropdown-item" title="<?php echo lng('Preview') ?>" href="#" onclick="starred_preview_show('<?php echo $istar; ?>')" data-toggle="lightbox" data-gallery="tiny-gallery" data-title="<?php echo fm_convert_win(fm_enc($f)) ?>" data-max-width="100%" data-width="100%"><?php echo lng('Preview') ?></a></li>
                                        <li> <a class="dropdown-item" title="<?php echo lng('DirectLink') ?>" href="<?php echo fm_enc(FM_IMAGE_URL . ($data['root_path'] != '' ? '/' . $data['root_path'] : '') . '/' . $data['file_name']) ?>" target="_blank"><?php echo lng('DirectLink') ?></a></li>
                                    <li><a class="dropdown-item" title="<?php echo lng('Download') ?>" href="?action=filemanager&p=<?php echo urlencode($data['root_path']) ?>&amp;dl=<?php echo urlencode($data['file_name']) ?>"><?php echo lng('Download') ?></a></li>
                                        <?php if (!FM_READONLY): ?>
                                           <!-- <li><a class="dropdown-item" title="<?php echo lng('Rename') ?>" href="#" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename') ?></a></li>-->
                                           <!-- <li><a class="dropdown-item" title="<?php echo lng('CopyTo') ?>..."
                                            href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('CopyTo') ?></a></li>-->
                                           <!-- <li><a href="#" onclick="checkit('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a>--> 
											 
											<!--<a class="dropdown-item" title="<?php echo lng('Starred') ?>..."
                                            href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred') ?></a></li>-->
											<li><a class="dropdown-item" title="<?php echo lng('Unstarred') ?>" href="?action=filemanager&p=&amp;starred&amp;starreddel=<?php echo $data['Id'] ?>" onclick="return confirm('<?php echo lng('Unstarred').' '.lng('File').'?'; ?>\n \n ( <?php echo urlencode($data['file_name']) ?> )');"> <?php echo lng('Unstarred') ?></a></li>
                                            <li><hr class="dropdown-divider" /></li>
											<li><a class="dropdown-item" href="#" onclick="sendtotrash('<?php echo urlencode(trim($data['root_path'] . '/' . $data['file_name'], '/')) ?>', <?php echo $istar ?>)"><span>Delete</span></a></li> 
										<?php endif; ?>
                                    </ul>
                                </td>
                            </tr>
	<!-- Code for the Show the Image/doc files in center -->
  <!--<div class="modal top fade" id="previewModal<?php echo $ik; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="false" data-mdb-keyboard="true">
  <div class="modal-dialog modal-xl ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $filelinkModel; ?></h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">-->
  <div id="starred_preview_div<?php echo $istar; ?>" style="display:none;">
  <?php //if (isset($_GET['view'])) {
	  //echo "File_name=>".$data['file_name'];
    $file = $data['file_name'];
	$quickView =  true;
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        fm_set_msg(lng('File not found'), 'error');
        //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    if(!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }
    
	
	$file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);

	$file_url = FM_IMAGE_URL. fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' .$file);

	$file_path = $path . '/' . $file;

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
	
    $mime_type = fm_get_mime_type($file_path);
    $filesize_raw = fm_get_size($file_path);
    $filesize = fm_get_filesize($filesize_raw);

    $is_zip = false;
    $is_gzip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;
    $is_onlineViewer = false;

    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text
    $online_viewer = strtolower(FM_DOC_VIEWER);

    if($online_viewer && $online_viewer !== 'false' && in_array($ext, fm_get_onlineViewer_exts())){
        $is_onlineViewer = true;
    }
    elseif ($ext == 'zip' || $ext == 'tar') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path, $ext);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }
   ?>
    <div class="filemanager-wrap flex-wrap">
	  <div><a href="#" onclick="starred_preview_div_close('<?php echo $istar; ?>')">Close</a></div>
        <div class="row ">
            <div class="col-12">
                <div class="card card-lg">
                <?php 
                    if($is_onlineViewer) {
                        if($online_viewer == 'google') {
                            echo '<iframe src="https://docs.google.com/viewer?embedded=true&hl=en&url=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        } else if($online_viewer == 'microsoft') {
                            echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        }
                    } elseif ($is_zip) {
                        // ZIP content
                        if ($filenames !== false) {
                            echo '<code class="maxheight">';
                            foreach ($filenames as $fn) {
                                if ($fn['folder']) {
                                    echo '<b>' . fm_enc($fn['name']) . '</b><br>';
                                } else {
                                    echo $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                                }
                            }
                            echo '</code>';
                        } else {
                            echo '<p>'.lng('Error while fetching archive info').'</p>';
                        }
                    } elseif ($is_image) {
                        // Image content
                        if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))) {
                            echo '<img src="' . fm_enc($file_url) . '" alt="" class="card-img-top preview-img">';
                        }
                    } elseif ($is_audio) {
                        // Audio content
                        echo '<p><audio src="' . fm_enc($file_url) . '" controls preload="metadata"></audio></p>';
                    } elseif ($is_video) {
                        // Video content
                        echo '<div class="preview-video"><video src="' . fm_enc($file_url) . '" width="640" height="360" controls preload="metadata"></video></div>';
                    } elseif ($is_text) {
                        if (FM_USE_HIGHLIGHTJS) {
                            // highlight
                            $hljs_classes = array(
                                'shtml' => 'xml',
                                'htaccess' => 'apache',
                                'phtml' => 'php',
                                'lock' => 'json',
                                'svg' => 'xml',
                            );
                            $hljs_class = isset($hljs_classes[$ext]) ? 'lang-' . $hljs_classes[$ext] : 'lang-' . $ext;
                            if (empty($ext) || in_array(strtolower($file), fm_get_text_names()) || preg_match('#\.min\.(css|js)$#i', $file)) {
                                $hljs_class = 'nohighlight';
                            }
                            $content = '<pre class="with-hljs"><code class="' . $hljs_class . '">' . fm_enc($content) . '</code></pre>';
                        } elseif (in_array($ext, array('php', 'php4', 'php5', 'phtml', 'phps'))) {
                            // php highlight
                            $content = highlight_string($content, true);
                        } else {
                            $content = '<pre>' . fm_enc($content) . '</pre>';
                        }
                        echo $content;
                    }
                    ?>
                    <?php if(!$quickView) { ?>
                        <div class="card-body">
                            <h5 class="card-title mb-4"><?php echo $view_title ?> "<?php echo fm_enc(fm_convert_win($file)) ?>"</h5>
                            <p class="break-word d-none">
                                Full path: <?php echo fm_enc(fm_convert_win($file_path)) ?><br>
                                File size: <?php echo ($filesize_raw <= 1000) ? "$filesize_raw bytes" : $filesize; ?><br>
                                MIME-type: <?php echo $mime_type ?><br>
                                <?php
                                // ZIP info
                                if (($is_zip || $is_gzip) && $filenames !== false) {
                                    $total_files = 0;
                                    $total_comp = 0;
                                    $total_uncomp = 0;
                                    foreach ($filenames as $fn) {
                                        if (!$fn['folder']) {
                                            $total_files++;
                                        }
                                        $total_comp += $fn['compressed_size'];
                                        $total_uncomp += $fn['filesize'];
                                    }
                                    ?>
                                    Files in archive: <?php echo $total_files ?><br>
                                    Total size: <?php echo fm_get_filesize($total_uncomp) ?><br>
                                    Size in archive: <?php echo fm_get_filesize($total_comp) ?><br>
                                    Compression: <?php echo round(($total_comp / $total_uncomp) * 100) ?>%<br>
                                    <?php
                                }
                                // Image info
                                if ($is_image) {
                                    $image_size = getimagesize($file_path);
                                    echo 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
                                }
                                // Text info
                                if ($is_text) {
                                    $is_utf8 = fm_is_utf8($content);
                                    if (function_exists('iconv')) {
                                        if (!$is_utf8) {
                                            $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                                        }
                                    }
                                    echo 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
                                }
                                ?>
                            </p>
                            <p>
                                <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-chevron-circle-left go-back"></i> <?php echo lng('Back') ?></a>
                                <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($file) ?>"><i class="fa fa-download"></i> <?php echo lng('Download') ?></a>
                                <a class="card-link" href="<?php echo fm_enc($file_url) ?>" target="_blank"><i class="fa fa-link"></i> <?php echo lng('Open') ?></a>
                                <?php
                                // ZIP actions
                                if (!FM_READONLY && ($is_zip || $is_gzip) && $filenames !== false) {
                                    $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                                    ?>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>"><i class="fa fa-check-circle"></i> <?php echo lng('UnZip') ?></a>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>&amp;tofolder=1" title="UnZip to <?php echo fm_enc($zip_name) ?>"><i class="fa fa-check-circle"></i>
                                            <?php echo lng('UnZipToFolder') ?></a>
                                    <?php
                                }
                                if ($is_text && !FM_READONLY) {
                                    ?>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>" class="edit-file"><i class="fa fa-pencil-square"></i> <?php echo lng('Edit') ?>
                                        </a>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&env=ace"
                                        class="edit-file"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?>
                                        </a>
                                <?php } ?>
                            </p>
                        </div>
                         <?php
                        } 
					  ?>
                </div>
              </div>
            </div>
          </div>
		</div>
      <!--</div>
    </div>
  </div>
</div>-->	
							
							<?php
							 }
							flush();
                          $istar++;	
                        }
                        if (empty($folders) && empty($files)) {
                            ?>
                            <tfoot>
                                <tr><?php if (!FM_READONLY): ?>
                                        <td></td><?php endif; ?>
                                    <td colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>"><em><?php echo lng('Folder is empty') ?></em></td>
                                </tr>
                            </tfoot>
                            <?php
                        } else {
                            ?>
							<?php
							$directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
							$directorysize=fm_get_directorysize($directory);
							$totalfreesize=1000000000 - $directorysize;
							$totalsizegb=sizeFormat($totalfreesize);
							$totalsizegbArr=explode('_', $totalsizegb);
							//fm_get_filesize(@disk_free_space($path))
                            ?>	 
                            <tfoot>
                                <tr><?php if (!FM_READONLY): ?>
                                    <td class="gray"></td><?php endif; ?>
                                    <td class="gray" colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>">
                                        <?php //echo lng('FullSize').': <span class="badge badge-light">'.fm_get_filesize($all_files_size).'</span>' ?>
                                        <?php echo lng('File').': <span class="badge badge-light">'.$row_file_cnt.'</span>' ?>
                                        <?php echo lng('Folder').': <span class="badge badge-light">'.$row_folder_cnt.'</span>' ?>
                                        <?php echo lng('PartitionSize').': <span class="badge badge-light">'.$totalsizegbArr[1].'GB</span> '.lng('FreeOf').' <span class="badge badge-light">1 GB</span>'; //fm_get_filesize(@disk_total_space($path)) ?>
                                    </td>
                                </tr>
                            </tfoot>
                            <?php
                        }
                        ?>
                    </table>
                </div>

                <div class="row" id="starred_preview_foot">
                    <?php if (!FM_READONLY): ?>
                    <div class="col-12">
                        <ul class="list-inline footer-action m-0">
                            <li class="list-inline-item"> <a href="#/select-all" class="btn btn-small btn-outline-primary btn-2" onclick="select_all();return false;"><i class="fa fa-check-square"></i> <?php echo lng('SelectAll') ?> </a></li>
                            <li class="list-inline-item"><a href="#/unselect-all" class="btn btn-small btn-outline-primary btn-2" onclick="unselect_all();return false;"><i class="fa fa-window-close"></i> <?php echo lng('UnSelectAll') ?> </a></li>
                            <li class="list-inline-item"><a href="#/invert-all" class="btn btn-small btn-outline-primary btn-2" onclick="invert_all();return false;"><i class="fa fa-th-list"></i> <?php echo lng('InvertSelection') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="delete" id="a-delete" value="Delete" onclick="return confirm('<?php echo lng('Delete selected files and folders?'); ?>')">
                                <a href="javascript:document.getElementById('a-delete').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-trash"></i> <?php echo lng('Delete') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="zip" id="a-zip" value="zip" onclick="return confirm('<?php echo lng('Create archive?'); ?>')">
                                <a href="javascript:document.getElementById('a-zip').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> <?php echo lng('Zip') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="tar" id="a-tar" value="tar" onclick="return confirm('<?php echo lng('Create archive?'); ?>')">
                                <a href="javascript:document.getElementById('a-tar').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> <?php echo lng('Tar') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="copy" id="a-copy" value="Copy">
                                <a href="javascript:document.getElementById('a-copy').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-files-o"></i> <?php echo lng('Copy') ?> </a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
			 </form>
			
		<?php //include('rightside.php');?>
		
	  <?php		  
	    //fm_show_footer($sizesPershow,$sucessmsg);
      //exit;
    //}
	?>