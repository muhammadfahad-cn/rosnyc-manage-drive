<div id="files_div">
	  <div class="my-file-inner">
        <div class="row mb-4">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h6>My Files</h6>
                <a class="font-size-18" href="javascript: void(0);">View All</a>
            </div>
        </div>
        <div class="row mb-4">
        <?php
                $ii = 3399;
                foreach ($folders as $key => $value){
                            if ($value == 'trash') {  unset($folders[$key]); }
                            if ($value == 'shared') {  unset($folders[$key]); }
                        }
                    foreach ($folders as $f) {
                        // Get No of files in the folder //
                        $cdirectory = $path . '/' . $f;
                        $cfilecount = 0;
                        $cfiles = array_slice(scandir($cdirectory),2);
                        if ($cfiles){
                            $cfilecount = count($cfiles);
                            }
                        $is_link = is_link($path . '/' . $f);
                        $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                        $modif_raw = filemtime($path . '/' . $f);
                        $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                        if ($calc_folder) {
                            $filesize_raw = fm_get_directorysize($path . '/' . $f);
                            $filesize = fm_get_filesize($filesize_raw);
                        }
                        else {
                            $filesize_raw = "";
                            $filesize = lng('Folder');
                        }
                        $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                        if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                            $owner = posix_getpwuid(fileowner($path . '/' . $f));
                            $group = posix_getgrgid(filegroup($path . '/' . $f));
                        } else {
                            $owner = array('name' => '?');
                            $group = array('name' => '?');
                        }
                        ?>
            <div class="col-sm-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="card shadow-none mb-4">
                    <div class="card-body d-flex align-items-start">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-transparent rounded">
                                <i class="fa fa-folder"></i>
                            </div>
                        </div>
                        <div class="d-flex ps-4 pt-2">
                            <div class="overflow-hidden me-auto">
                                <h5 class="font-size-20 text-truncate mb-1"><a href="javascript: void(0);"
                                        ><a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo fm_convert_win(fm_enc($f)) ?>
                                    </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></a></h5>
                                <p class="text-truncate mb-0 text-300"><?php echo $cfilecount; ?> Files</p>
                            </div>
                            <!-- <div class="align-self-end ms-2">
                                <p class="text-muted mb-0 text-300"> <?php echo $filesize_raw; ?></p>
                            </div> -->
                        </div>
                        <div class="float-end ms-auto">
                            <div class="dropdown pt-2">
                                <a class="font-size-16 text-muted" href="#" role="button" id="subDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="subDropdownMenuLink">
                                    <li><a class="dropdown-item" title="<?php echo lng('Open') ?>" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>">Open</a></li>
                                    <!--<li><a class="dropdown-item" href="#">Edit</a></li>-->
                                    <li><a class="dropdown-item" title="<?php echo lng('Rename')?>" href="#" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename')?></a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" title="<?php echo lng('Delete') ?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('<?php echo lng('Delete').' '.lng('File').'?'; ?>\n \n ( <?php echo urlencode($f) ?> )');"> <?php echo lng('Delete') ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                flush();
                $ii++;
            }
            ?>		
            <!-- end col -->
        </div>
    </div>
    
	<script src="https://rosnyc.com/dev_admin/assets/mdb/js/mdb.min.js"></script>
    <link rel="stylesheet" href="https://rosnyc.com/admin/assets/mdb/css/mdb.min.css" />
        <div class="d-flex flex-wrap">
            <h5 class="me-3">Recent Files</h5>
			 <div class="ms-auto">
                    <a href="javascript: void(0);" class="font-size-18">View All</a>
                </div>
        </div>
		   <form action="" method="post" class="w-100" novalidate>
                <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                <input type="hidden" name="group" value="1">
                <div class="m-table-responsive">
				<div id="loader-storage" style="display:none;">
                    <div class="loading-box" id="" style="/* display: none; */">
                        <div class="loader"></div>
                    </div>
                </div>
				<div id="loader" style="display:none;">
                    <div class="loading-box" id="" style="/* display: none; */">
                        <div class="loader"></div>
                    </div>
                </div>
				  <div class="alert alert-danger response-error" style="display:none;" role="alert">
				     <div id="error-message"> </div>
				  </div>
				<div id="getAllStorageHTML" style="display:block;">
                    <table class="table table-hover table-sm <?php echo $tableTheme; ?>" id="main-table">
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
						?>
						
						<?php
                        $ii = 3399;
						foreach ($folders as $f) {
                            $is_link = is_link($path . '/' . $f);
                            $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                            $modif_raw = filemtime($path . '/' . $f);
                            $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            if ($calc_folder) {
                                $filesize_raw = fm_get_directorysize($path . '/' . $f);
                                $filesize = fm_get_filesize($filesize_raw);
                            }
                            else {
                                $filesize_raw = "";
                                $filesize = lng('Folder');
                            }
                            $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                            if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                $group = posix_getgrgid(filegroup($path . '/' . $f));
                            } else {
                                $owner = array('name' => '?');
                                $group = array('name' => '?');
                            }
                            ?>
                            <tr id="<?php echo $ii ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="<?php echo $ii ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                                            <label class="custom-control-label" for="<?php echo $ii ?>"></label>
                                        </div>
                                    </td><?php endif; ?>
                                    <td data-sort=<?php echo fm_convert_win(fm_enc($f)) ?>>
                                        <div class="filename">
										<?php if(isset($_GET['sharedu'])){ ?>
										<a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')).'&sharedu='.$_GET['sharedu'] ?>"><span class="thumb"><i class="<?php echo $img ?>"></i></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
											<?php } else { ?>
										<a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><span class="thumb"><i class="<?php echo $img ?>"></i></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
										<?php } ?>
											<?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
										
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
                                            <a class="font-size-16 text-muted" href="#" role="button" id="dropdownMenuLink"
                                                data-mdb-toggle="dropdown" aria-expanded="false" > <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <li> <a class="dropdown-item" title="<?php echo lng('DirectLink')?>" href="<?php echo fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f . '/') ?>" target="_blank"><?php echo lng('DirectLink')?></a><li>
                                            <?php if (!FM_READONLY): ?>
                                                <li><a class="dropdown-item" title="<?php echo lng('Rename')?>" href="#" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename')?></a></li>
                                                <li><a class="dropdown-item" title="<?php echo lng('CopyTo')?>..." href="?action=filemanager&p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('CopyTo')?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="checkit('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a><!--<a class="dropdown-item" title="<?php echo lng('Starred')?>..." href="?action=filemanager&p=&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred')?></a>--></li>
												<li><a class="dropdown-item" title="<?php echo lng('Share') ?>" href="#" data-toggle="modal" data-target="#shareModal<?php echo $ii; ?>"><?php echo lng('Share') ?></a></li>
												<li><hr class="dropdown-divider" /></li>
                                                <li><a class="dropdown-item" href="#" onclick="sendtotrash('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>', <?php echo $ii ?>)"><span>Delete</span></a></li>
												<!--<li><a class="dropdown-item" title="<?php echo lng('Delete')?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('<?php echo lng('Delete').' '.lng('Folder').'?'; ?>\n \n ( <?php echo urlencode($f) ?> )');"> <?php echo lng('Delete')?></a><li>-->
                                            <?php endif; ?>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            flush();
							?>
<!----------- Modal ------------------------>
<script>
 function myFunction(sharedId) {
  /* Get the text field */
 
  var copyText = document.getElementById("copysharedlink"+sharedId);
  
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
 </script>							
  <div class="modal fade" id="shareModal<?php echo $ii; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
     <div class="modal-content">
	   <div class="modal-header">
	   <h5 class="modal-title" id="exampleModalLabel">Share <?php echo fm_convert_win(fm_enc($f)) ?></h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
			</button>
			  </div>
			  <div id="loader-model<?php echo $ii; ?>" style="display:none;"><img  style="z-index: 999;" src="https://rosnyc.com/admin/filemanager/load.webp" class="ajax-loader"></div>
	            <div class="alert alert-danger response-error-model<?php echo $ii; ?>" style="display:none;" role="alert">
		           <div id="error-message-model<?php echo $ii; ?>"> </div>
               </div>
			     
				  <div class="modal-body">
					    <input class="form-control" type="text" id="share_emails<?php echo $ii?>" name="share_emails" value="" required>
						<input class="form-control" type="hidden" name="root_path" value="<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>">
						 <p></p>
					<p>Anyone on the Internet with the link can view</p>
					<?php  
					$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
					$shared_u='&sharedu='.base64_encode($_SESSION['userId']);
					$FM_PATH=urlencode(trim(FM_PATH . '/' . $f, '/'));
					$sharedURLfolder = fm_enc($FM_SHARED_URL.($FM_PATH != '' ? '/' . $FM_PATH : '').$shared_u);
					?>
					<p>
					<input class="form-control" type="text" id="copysharedlink<?php echo $$ii; ?>" name="shared_url" value="<?php echo trim($sharedURLfolder);?>" readonly/>
					</p>	   
						  </div>
							<div class="modal-footer">
							  <button type="button" class="btn btn-secondary" onclick="myFunction('<?php echo $ii; ?>')">Copy Link</button>
							  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							  <button type="button" onclick="sharedata('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>','<?php echo $ii; ?>')" class="btn btn-primary">Share</button>
					   </div>
					  
				   </div>
			     </div>
		      </div>
				 <?php                          
				  $ii++;
				}
				$ik = 6070;
						foreach ($files as $f) {
							$is_link = is_link($path . '/' . $f);
                            $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
                            $modif_raw = filemtime($path . '/' . $f);
                            $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            $filesize_raw = fm_get_size($path . '/' . $f);
                            $filesize = fm_get_filesize($filesize_raw);
                            $filelink = '?action=filemanager&p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
                            $filelinkModel = $f;
							$all_files_size += $filesize_raw;
                            $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                            if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                $group = posix_getgrgid(filegroup($path . '/' . $f));
                            } else {
                                $owner = array('name' => '?');
                                $group = array('name' => '?');
                            }
                            ?>
                            <tr id="<?php echo $ik; ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                                        <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                                    </div>
                                    </td><?php endif; ?>
                                <td data-sort=<?php echo fm_enc($f) ?>>
                                    <div class="filename">
									<?php if(isset($_GET['sharedu'])){ 
										$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
										$shared_file='&shared='.$_GET['sharedu'].'&sharedview='.$f;
										$sharedURL = fm_enc($FM_SHARED_URL.(FM_PATH != '' ? '/' . FM_PATH : '').$shared_file);
									    $sharedURLPreviewPath='https://'.$http_host.'/admin/filemanager/'.$_GET['sharedu'].'/'.(FM_PATH != '' ? '/' . FM_PATH : '').'/'.$f;
									 if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                    <?php $imagePreview = $sharedURLPreviewPath; ?>
                                            <a href="<?php echo $sharedURL ?>"  data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fm_enc($f) ?>">
                                    <?php else: ?>
                                            <a href="<?php echo $sharedURL ?>" title="<?php echo $f ?>">
                                    <?php endif; ?>
                                            <span><img src="<?php echo $imagePreview ?>" alt="img"></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
									<?php } else { ?>
                                    <?php
                                    if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                    <?php $imagePreview = fm_enc(FM_IMAGE_URL.(FM_PATH != '' ? '/' . FM_PATH : '').'/'.$f); ?>
                                            <a href="#" onclick="files_preview_show('<?php echo $ik; ?>')" data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fm_enc($f) ?>">
                                    <?php else: ?>
                                            <a href="#" onclick="files_preview_show('<?php echo $ik; ?>')" title="<?php echo $f ?>">
                                    <?php endif; ?>
                                                <span onclick="files_preview_show('<?php echo $ik; ?>')"><img src="<?php echo $imagePreview ?>" alt="img"></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
									<?php } ?>
											
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
                                    <a class="font-size-16 text-muted" href="#" role="button" id="secondDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false" > <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="secondDropdownMenuLink">
									<li><a class="dropdown-item" title="<?php echo lng('Preview') ?>"  href="#" onclick="files_preview_show('<?php echo $ik; ?>')"  data-title="<?php echo fm_convert_win(fm_enc($f)) ?>" data-max-width="100%" data-width="100%"><?php echo lng('Preview') ?></a></li>
									 <!--<li> <a class="dropdown-item" title="<?php echo lng('Preview') ?>" href="<?php echo $filelink.'&quickView=1'; ?>" data-toggle="lightbox" data-gallery="tiny-gallery" data-title="<?php echo fm_convert_win(fm_enc($f)) ?>" data-max-width="100%" data-width="100%"><?php echo lng('Preview') ?></a></li>-->
                                        <li> <a class="dropdown-item" title="<?php echo lng('DirectLink') ?>" href="<?php echo fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f) ?>" target="_blank"><?php echo lng('DirectLink') ?></a></li>
                                        <li><a class="dropdown-item" title="<?php echo lng('Download') ?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($f) ?>"><?php echo lng('Download') ?></a></li>
                                        <?php if (!FM_READONLY): ?>
                                            <li><a class="dropdown-item" title="<?php echo lng('Rename') ?>" href="#" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename') ?></a></li>
                                            <li><a class="dropdown-item" title="<?php echo lng('CopyTo') ?>..."
                                            href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('CopyTo') ?></a></li>
                                            <li><a class="dropdown-item" href="#" onclick="checkit('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a>
                                            											
											<!--<a class="dropdown-item" title="<?php echo lng('Starred') ?>..."
                                            href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred') ?></a></li>-->
											<li><a class="dropdown-item" title="<?php echo lng('Share') ?>" href="#" data-toggle="modal" data-target="#shareModal<?php echo $ik; ?>"><?php echo lng('Share') ?></a></li>
											<li><hr class="dropdown-divider" /></li>
                                            <!--<li> <a class="dropdown-item" title="<?php echo lng('Delete') ?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('<?php echo lng('Delete').' '.lng('File').'?'; ?>\n \n ( <?php echo urlencode($f) ?> )');"> <?php echo lng('Delete') ?></a></li>-->
											<li><a class="dropdown-item" href="#" onclick="sendtotrash('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>',<?php echo $ik; ?>)"><span>Delete</span></a> 
                                        <?php endif; ?>
                                    </ul>
								  </div>	
                                </td>
                            </tr>
                            <?php
                               flush();
                            ?>
							
 <!------------- Modal ------------------------->
 <script>
 function myFunction(sharedId) {
  /* Get the text field */
 
  var copyText = document.getElementById("copysharedlink"+sharedId);
  
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
 </script>
  <div class="modal fade share-modal" id="shareModal<?php echo $ik; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
     <div class="modal-content">
	   <div class="modal-header">
	   <h5 class="modal-title" id="exampleModalLabel">Share <?php echo fm_convert_win(fm_enc($f)) ?></h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
			</button>
			  </div>
			  <div id="loader-model<?php echo $ik; ?>" style="display:none;"><img  style="z-index: 999;" src="https://rosnyc.com/admin/filemanager/load.webp" class="ajax-loader"></div>
	            <div class="alert alert-danger response-error-model<?php echo $ik; ?>" style="display:none;" role="alert">
		           <div id="error-message-model<?php echo $ik; ?>"> </div>
               </div>
			     <div class="modal-body">
					<input class="form-control" type="text" id="share_emails<?php echo $ik?>" name="share_emails" value="" required>
					<input class="form-control" type="hidden" name="root_path" value="<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>">
					<p></p>
					<p class="mb-1">Anyone on the Internet with the link can view</p>
					<?php  
					$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
					$shared_file='&shared='.base64_encode($_SESSION['userId']).'&sharedview='.$f;
					$sharedURL = fm_enc($FM_SHARED_URL.(FM_PATH != '' ? '/' . FM_PATH : '').$shared_file);
					?>
					<div class="d-flex copy-link">
					<input class="form-control" type="text" id="copysharedlink<?php echo $ik; ?>" name="shared_url" value="<?php echo trim($sharedURL);?>" readonly/>
                    <button type="button" class="btn btn-secondary" onclick="myFunction('<?php echo $ik; ?>')">Copy Link</button>
                    </div>	  
					</div>
					<div class="modal-footer">
						<button type="button" onclick="sharedata('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>','<?php echo $ik; ?>')" class="btn btn-primary btn-rounded">Share</button>
					 </div>
					</div>
			     </div>
		      </div>
			  

<!-- Button trigger modal -->
<!--<button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
  Launch demo modal
</button>-->

<!-- Modal -->
<!--<div class="modal top fade" id="previewModal<?php echo $ik; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="false" data-mdb-keyboard="true">
  <div class="modal-dialog modal-xl ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $filelinkModel; ?></h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">-->
  <div id="files_preview_div<?php echo $ik; ?>" style="display:none;">
  <?php //if (isset($_GET['view'])) {
    $file = $filelinkModel;
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
	  <div><a href="#" style="float:left" onclick="files_preview_div_close('<?php echo $ik; ?>')">Close</a></div>
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
				       $ik++;
                    }
				   ?>
				
                <?php				
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
                                        <?php echo lng('FullSize').': <span class="badge badge-light me-2">'.fm_get_filesize($all_files_size).'</span>' ?>
                                        <?php echo lng('File').': <span class="badge badge-light me-2">'.$num_files.'</span>' ?>
                                        <?php echo lng('Folder').': <span class="badge badge-light me-2">'.$num_folders.'</span>' ?>
                                        <?php echo lng('PartitionSize').': <span class="badge badge-light me-2">'.$totalsizegbArr[1].'GB</span> '.lng('FreeOf').' <span class="badge badge-light">1 GB</span>'; //fm_get_filesize(@disk_total_space($path)) ?>
                                    </td>
                                </tr>
                            </tfoot>
                            <?php
                           }
                        ?>
                    </table>
					</div>
				    <div id="getStorageHTML"></div>
                </div>
                  <div class="row" id="files_preview_foot">
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
			 </div>