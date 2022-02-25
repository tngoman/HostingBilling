<ul class="nav">
<?php foreach ($tickets as $key => $t) {
		if($t->status == 'open'){ $s_label = 'danger'; }elseif($t->status=='closed'){ $s_label = 'success'; }elseif($t->status=='resolved'){ $s_label = 'primary'; }else{ $s_label = 'default'; }
?>
		<li class="b-b b-light <?php if($t->id == $this->uri->segment(3)){ echo "bg-light dk"; } ?>">
			<a href="<?=base_url()?>tickets/view/<?=$t->id?>"><?=$t->ticket_code?>
				<div class="pull-right">

									<?php
                                    switch ($t->status) {
                                        case 'open':
                                            $status_lang = 'open';
                                            break;
                                        case 'closed':
                                            $status_lang = 'closed';
                                            break;
                                        case 'pending':
                                            $status_lang = 'pending';
                                            break;
                                        case 'resolved':
                                            $status_lang = 'resolved';
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }?>

					<?php if($t->status == 'closed'){ $label = 'success'; } else{ $label = 'danger'; } ?>

					<span class="label label-<?=$s_label?>"><?=ucfirst(lang($status_lang))?> </span>

					</div> <br>
				<small class="block small text-muted">
				<?php if($t->reporter != NULL){ ?>
				<?=ucfirst(User::displayName($t->reporter))?>
					<?php } else{ echo "NULL"; } ?>
					<span class="pull-right"><?=strtolower(Applib::time_elapsed_string(strtotime($t->created)));?></span>
				</small>
								</a>
								</li>
		<?php } ?>
</ul>
