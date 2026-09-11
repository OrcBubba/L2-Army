<?php 
if(!defined('l2jmobius')) {
    die('Direct access not permitted');
} 
?><div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"><?=_('ACM Settings');?></h1>
	<p class="mb-4"><?=_('Edit the settings of L2jMobius ACM.');?></p>
	
	<?php if(isset($alert)){
		echo '
		<div class="alert alert-'.$alert['type'].'">'.$alert['message'].'</div>'; 
	} ?>

	<div class="row">
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Email settings');?></h6>
				</div>
				<div class="card-body">
					<form id="email-form" method="post">
						<div class="form-group">
							<label><?=_('Your email address');?></label>
							<input type="email" class="form-control" placeholder="<?=_('Enter your email');?>" required value="<?=$settings->get('email');?>" name="email">
							<small class="form-text text-muted"><?=_('This will be the sender for all system emails.');?></small>
						</div>
						<div class="form-group">
							<label><?=_('How will you send the emails?');?></label>
							<select class="form-control" name="use_mailjet" required>
								<option value="1">Via Mailjet (Faster)</option>
								<option value="0"<?php if(!$settings->check('use_mailjet')) echo ' selected'; ?>>Through SMTP</option>
							</select>
						</div>
						<div id="smtp-info"<?php if($settings->check('use_mailjet')) echo ' class="d-none"'; ?>>
							<hr />
							<div class="form-group">
								<label>SMTP Host</label>
								<input type="text" class="form-control" placeholder="Enter your smtp host" value="<?=$settings->get('smtp_host');?>" name="smtp_host"<?php if(!$settings->check('use_mailjet')) echo ' required'; ?>>
							</div>
							<div class="form-group">
								<label>SMTP Username</label>
								<input type="text" class="form-control" placeholder="Enter your smtp username" value="<?=$settings->get('smtp_username');?>" name="smtp_username"<?php if(!$settings->check('use_mailjet')) echo ' required'; ?>>
							</div>
							<div class="form-group">
								<label>SMTP Password</label>
								<input type="text" class="form-control" placeholder="Enter your smtp password" value="<?=$settings->get('smtp_password');?>" name="smtp_password"<?php if(!$settings->check('use_mailjet')) echo ' required'; ?>>
							</div>
							<div class="row">
								<div class="col-md-7">
									<div class="form-group">
										<label>SMTP Encryption</label>
										<select class="form-control" name="smtp_encryption"<?php if(!$settings->check('use_mailjet')) echo ' required'; ?>>
											<option value="none">None</option>
											<option value="tls"<?php if($settings->get('smtp_encryption') == 'tls') echo ' selected'; ?>>TLS</option>
											<option value="ssl"<?php if($settings->get('smtp_encryption') == 'ssl') echo ' selected'; ?>>SSL</option>
										</select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label>SMTP Port</label>
										<input type="number" class="form-control" placeholder="Port number" value="<?=$settings->get('smtp_port');?>" name="smtp_port"<?php if(!$settings->check('use_mailjet')) echo ' required'; ?>>
									</div>
								</div>
							</div>
						</div>
						<div id="mailjet-info"<?php if(!$settings->check('use_mailjet')) echo ' class="d-none"'; ?>>
							<hr />
							<div class="form-group">
								<label>Mailjet API key</label>
								<input type="text" class="form-control" placeholder="Enter your smtp host" value="<?=$settings->get('mailjet_username');?>" name="mailjet_username"<?php if($settings->check('use_mailjet')) echo ' required'; ?>>
							</div>
							<div class="form-group">
								<label>Mailjet Secret key</label>
								<input type="text" class="form-control" placeholder="Enter your Mailjet secret key" value="<?=$settings->get('mailjet_password');?>" name="mailjet_password"<?php if($settings->check('use_mailjet')) echo ' required'; ?>>
							</div>
							
							<small class="form-text text-muted mb-2">In order to use Mailjet, <a href="https://mailjet.com" target="_blank">create a free account</a> and get your API details.</small>
						</div>
						<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('General settings');?></h6>
				</div>
				<div class="card-body">
					<form id="general-form" method="post">
						<div class="form-group">
							<label><?=_('Default language');?></label>
							<select class="form-control" name="default_language" required>
							<?php foreach($locales as $key=>$value){
								if($key == $defaultLanguage)
									echo '<option value="'.$key.'" selected>'.$value['name'].'</option>';
								else
									echo '<option value="'.$key.'">'.$value['name'].'</option>';
							} ?>
							</select>
						</div>
						<div class="form-group">
							<label><?=_('Allow account creations?');?></label>
							<select class="form-control" name="disable_registration" required>
								<option value="0"><?=_('Yes');?></option>
								<option value="1"<?php if($settings->check('disable_registration')) echo ' selected'; ?>><?=_('No');?></option>
							</select>
						</div>
						<div class="form-group">
							<label><?=_('Require account email verification?');?></label>
							<select class="form-control" name="require_verification" required>
								<option value="0"><?=_('No');?></option>
								<option value="1"<?php if($settings->check('require_verification')) echo ' selected'; ?>><?=_('Yes');?></option>
							</select>
						</div>
						<div class="form-group">
							<label><?=_('Your server\'s main website');?></label>
							<input class="form-control" name="main_website" placeholder="https://..." value="<?=$settings->get('main_website');?>">
						</div>
						<div class="form-group">
							<label><?=_('Enable ACM Task Manager?');?></label>
							<select class="form-control" name="enable_task_manager" required>
								<option value="0"><?=_('No');?></option>
								<option value="1"<?php if($settings->check('enable_task_manager')) echo ' selected'; ?>><?=_('Yes');?></option>
							</select>
						</div>
						<small class="form-text text-muted mb-2">This allows you to perform in-game commands using the ACM. You must add the ACM Task Manager to your Java project. You can find the code at <a href="https://pastebin.com/EtSJCgUR" target="_blank">https://pastebin.com/EtSJCgUR</a>. This is made for CT_0 version of Mobius, but feel free to modify it to your needs. Don't forget to include it at your GameServer.java.</small>
						
						<div class="form-group">
							<label><?=_('Display server\'s stats?');?></label>
							<select class="form-control" name="show_stats" required>
								<option value="0"><?=_('No');?></option>
								<option value="1"<?php if($settings->check('show_stats')) echo ' selected'; ?>><?=_('Yes');?></option>
							</select>
						</div>
						<div id="server-stats"<?php if(!$settings->check('show_stats')) echo ' class="d-none"'; ?>>
							<div class="form-group">
								<label><?=_('Display loginserver\'s status?');?></label>
								<select class="form-control" name="show_login_status" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('show_login_status')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
							<div class="form-group">
								<label><?=_('Display gameserver\'s status?');?></label>
								<select class="form-control" name="show_game_status" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('show_game_status')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
							<div class="form-group">
								<label><?=_('Display online player count?');?></label>
								<select class="form-control" name="show_online_players" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('show_online_players')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
							<div class="form-group">
								<label><?=_('Display online GM count?');?></label>
								<select class="form-control" name="show_online_gms" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('show_online_gms')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
						</div>
						
						<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xl-4">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary"><?=_('Donation settings');?></h6>
				</div>
				<div class="card-body">
					<form id="donation-form" method="post">
						<div class="form-group">
							<label><?=_('Enable donations?');?></label>
							<select class="form-control" name="enable_donations" required>
								<option value="0"><?=_('No');?></option>
								<option value="1"<?php if($settings->check('enable_donations')) echo ' selected'; ?>><?=_('Yes');?></option>
							</select>
						</div>
						<div id="donation-settings"<?php if(!$settings->check('enable_donations')) echo ' class="d-none"'; ?>>
							<div class="form-group">
								<label><?=_('Use internal balance?');?></label>
								<select class="form-control" name="use_balance" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('use_balance')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
							<small class="form-text text-muted mb-2"><?=_('Internal balance means that players can prepay for donations and spend their balance in order to get items.');?></small>
							<div class="form-group">
								<label><?=_('Donation currency');?></label>
								<select class="form-control" name="currency" required>
									<option value="EUR">EUR (€)</option>
									<option value="USD"<?php if($settings->get('currency') == 'USD') echo ' selected'; ?>>USD ($)</option>
									<option value="BRL"<?php if($settings->get('currency') == 'BRL') echo ' selected'; ?>>BRL (R$)</option>
									<option value="RUB"<?php if($settings->get('currency') == 'RUB') echo ' selected'; ?>>RUB (₽)</option>
								</select>
							</div>
							<div class="form-group">
								<label><?=_('Terms of your service');?></label>
								<textarea class="form-control" name="donations_tos" required rows="10"><?=$settings->has('donations_tos') ? $settings->get('donations_tos') : 'Our server is and will always be Free-to-Play and a Play-to-Win server. Donation rewards are only time-saving perks. Donations help us keep the server alive and advertise it to new players.'; ?></textarea>
							</div>
							<small class="form-text text-muted mb-2">This textbox uses Markdown. Learn all the available syntax <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">here</a>.</small>
							<div class="form-group">
								<label><?=_('Enable Stripe?');?></label>
								<select class="form-control" name="enable_stripe" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->check('enable_stripe')) echo ' selected'; ?>><?=_('Yes');?></option>
								</select>
							</div>
							<div id="stripe-settings"<?php if(!$settings->check('enable_stripe')) echo ' class="d-none"'; ?>>
								<div class="form-group">
									<label>Stripe's Publishable key</label>
									<input type="text" class="form-control" name="stripe_key" placeholder="Type your Stripe's API key" value="<?=$settings->get('stripe_key');?>"<?php if($settings->check('enable_stripe')) echo ' required'; ?>>
								</div>
								<div class="form-group">
									<label>Stripe's Secret key</label>
									<input type="text" class="form-control" placeholder="Type your Stripe's API secret key" readonly value="You must add it inside your .env file">
								</div>
								<small class="form-text text-muted mb-2"><?=_('For security reasons, you have to fill-in your secret key inside your .env file.');?> You can find your keys from Stripe's <a href="https://dashboard.stripe.com/apikeys" target="_blank">Developers dashboard</a>.</small>
								<div class="form-group">
									<label>Webhook token</label>
									<input type="text" class="form-control" name="stripe_webhook" placeholder="Type your Stripe's webhook key" value="<?=$settings->get('stripe_webhook');?>"<?php if($settings->check('enable_stripe')) echo ' required'; ?>>
								</div>
								<div class="form-group">
									<small class="form-text text-muted mb-2"><?=_('In order to generate your webhook key you need to type your Endpoint URL in Stripe.');?> Your URL is:</small> <input type="text" readonly class="form-control" value="<?=$appURL;?>/en/webhooks/stripe">
								</div>
							</div>
							<div class="form-group">
								<label><?=_('Enable Paypal?');?></label>
								<select class="form-control" name="enable_paypal" required>
									<option value="0"><?=_('No');?></option>
									<option value="1"<?php if($settings->get('enable_paypal') == 1) echo ' selected'; ?>><?=_('Yes');?> (Live mode)</option>
									<option value="2"<?php if($settings->get('enable_paypal') == 2) echo ' selected'; ?>><?=_('Yes');?> (Sandbox mode)</option>
								</select>
							</div>
							<div id="paypal-settings"<?php if(!$settings->check('enable_paypal') && $settings->get('enable_paypal') != 2) echo ' class="d-none"'; ?>>
								<div class="form-group">
									<label>Paypal email</label>
									<input type="email" class="form-control" name="paypal_email" placeholder="<?=_('Type your Paypal email');?>" value="<?=$settings->get('paypal_email');?>"<?php if($settings->check('enable_paypal')) echo ' required'; ?>>
								</div>
							</div>
						</div>
						
						<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex align-items-center justify-content-between">
			<h6 class="m-0 font-weight-bold text-primary"><?=_('Donation item list');?></h6>
			<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-donation"><?=_('Add item');?></button>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><?=_('Item ID');?></th>
							<th><?=_('Item Name');?></th>
							<th><?=_('Quantity');?></th>
							<th><?=_('Price');?></th>
							<th><?=_('Allow multiple');?></th>
							<th><?=_('Max quantity');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_('Item ID');?></th>
							<th><?=_('Item Name');?></th>
							<th><?=_('Quantity');?></th>
							<th><?=_('Price');?></th>
							<th><?=_('Allow multiple');?></th>
							<th><?=_('Max quantity');?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php
					foreach($items as $row){
						if($row->allow_multiple == 1){
							$row->allow_multiple = '<span class="badge badge-success">'._('Yes').'</span>';
							if($row->max_quantity == '')
								$row->max_quantity = _('Unlimited');
						}
						else {
							$row->allow_multiple = '<span class="badge badge-warning">'._('No').'</span>';
							$row->max_quantity = null;
						}
						echo '
						<tr>
							<td><a href="#" class="edit-item" data-id="'.$row->id.'">'.$row->item_id.'</a></td>
							<td><a href="#" class="edit-item" data-id="'.$row->id.'">'.$row->name.'</a></td>
							<td>'.$row->item_quantity.'</td>
							<td>'.formatAmount($row->price).'</td>
							<td>'.$row->allow_multiple.'</td>
							<td>'.$row->max_quantity.'</td>
						</tr>';
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="add-donation" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="new-item-form">
				<input type="hidden" name="new_donation_item" value="1">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Add donation item');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Item ID');?></label>
						<input type="number" class="form-control" name="item_id" placeholder="<?=_('Type the item\'s in-game ID');?>" required>
					</div>
					<div class="form-group">
						<label><?=_('Item\'s Name');?></label>
						<input type="text" class="form-control" name="name" placeholder="<?=_('Type the name of the item');?>" required>
					</div>
					<div class="form-group">
						<label><?=_('Item Description (Optional)');?></label>
						<input type="text" class="form-control" name="description" placeholder="<?=_('This description will appear on each item');?>">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Quantity');?></label>
								<input type="number" class="form-control" name="item_quantity" placeholder="<?=_('Type the item\'s quantity');?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Price');?> (<?=($settings->has('currency')) ? $settings->get('currency') : 'EUR'; ?>)</label>
								<input type="number" class="form-control" name="price" placeholder="<?=_('Type the price');?>" required step=".01">
							</div>
						</div>
					</div>
					<div class="form-group">
						<small class="form-text text-muted mb-2"><?=_('Decimals are allowed by using "." for decimal point. The price is not per item. It refers to the above quantity. For example, if you wish to sell 200 Αdenas for 1€, you type quantity 200 and price 1. If you want the player to be able to buy many of these, you can allow multiple orders below. This will let people buy sets of this listing, like 400 Adenas for 2€.');?></small>
					</div>
					<div class="form-group">
						<label><?=_('Allow multiple orders');?></label>
						<select class="form-control" name="allow_multiple" required>
							<option value="0"><?=_('No');?></option>
							<option value="1"><?=_('Yes');?></option>
						</select>
					</div>
					<div class="max-order-quantity d-none">
						<div class="form-group">
							<label><?=_('Maximum order quantity (Optional)');?></label>
							<input type="number" class="form-control" name="max_quantity" placeholder="<?=_('Leave empty for unlimited');?>">
						</div>
						<small class="form-text text-muted mb-2"><?=_('This is the maximum number a user is allowed to add this item to his "cart". For example, if you are selling 200 Adenas for 1€ above, you can limit the total number of Adenas to 1000 Adenas by typing the number 5. This will allow the user to only buy up to 5 times the 200 Adenas pack.');?></small>
					</div>
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
					<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="edit-donation" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="edit-item-form">
				<input type="hidden" name="edit_donation_item">
				<div class="modal-header">
					<h5 class="modal-title"><?=_('Edit donation item');?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label><?=_('Item ID');?></label>
						<input type="number" class="form-control" name="item_id" placeholder="<?=_('Type the item\'s in-game ID');?>" required>
					</div>
					<div class="form-group">
						<label><?=_('Item\'s Name');?></label>
						<input type="text" class="form-control" name="name" placeholder="<?=_('Type the name of the item');?>" required>
					</div>
					<div class="form-group">
						<label><?=_('Item Description (Optional)');?></label>
						<input type="text" class="form-control" name="description" placeholder="<?=_('This description will appear on each item');?>">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Quantity');?></label>
								<input type="number" class="form-control" name="item_quantity" placeholder="Type the item's quantity" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><?=_('Price');?> (<?=($settings->has('currency')) ? $settings->get('currency') : 'EUR'; ?>)</label>
								<input type="number" class="form-control" name="price" placeholder="<?=_('Type the price');?>" required step=".01">
							</div>
						</div>
					</div>
					<div class="form-group">
						<small class="form-text text-muted mb-2"><?=_('Decimals are allowed by using "." for decimal point. The price is not per item. It refers to the above quantity. For example, if you wish to sell 200 Αdenas for 1€, you type quantity 200 and price 1. If you want the player to be able to buy many of these, you can allow multiple orders below. This will let people buy sets of this listing, like 400 Adenas for 2€.');?></small>
					</div>
					<div class="form-group">
						<label><?=_('Allow multiple orders');?></label>
						<select class="form-control" name="allow_multiple" required>
							<option value="0"><?=_('No');?></option>
							<option value="1"><?=_('Yes');?></option>
						</select>
					</div>
					<div class="max-order-quantity d-none">
						<div class="form-group">
							<label><?=_('Maximum order quantity (Optional)');?></label>
							<input type="number" class="form-control" name="max_quantity" placeholder="Leave empty for unlimited">
						</div>
						<small class="form-text text-muted mb-2"><?=_('This is the maximum number a user is allowed to add this item to his "cart". For example, if you are selling 200 Adenas for 1€ above, you can limit the total number of Adenas to 1000 Adenas by typing the number 5. This will allow the user to only buy up to 5 times the 200 Adenas pack.');?></small>
					</div>
				
				</div>
				<div class="modal-footer d-flex align-items-center justify-content-between">
					<button type="button" class="btn btn-danger btn-sm" id="delete-donation-item"><?=_('Delete');?></button>
					<div>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_('Close');?></button>
						<button type="submit" class="btn btn-primary"><?=_('Save');?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>