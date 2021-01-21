<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->integer('per_page')->default(20);
			$table->string('site_name', 100)->default('Snipe IT Asset Management');
			$table->integer('qr_code')->nullable();
			$table->string('qr_text', 32)->nullable();
			$table->integer('display_asset_name')->nullable();
			$table->integer('display_checkout_date')->nullable();
			$table->integer('display_eol')->nullable();
			$table->integer('auto_increment_assets')->default(0);
			$table->string('auto_increment_prefix')->default('0');
			$table->boolean('load_remote')->default(1);
			$table->string('logo')->nullable();
			$table->string('header_color')->nullable();
			$table->string('alert_email')->nullable();
			$table->boolean('alerts_enabled')->default(1);
			$table->text('default_eula_text')->nullable();
			$table->string('barcode_type')->nullable()->default('QRCODE');
			$table->string('slack_endpoint')->nullable();
			$table->string('slack_channel')->nullable();
			$table->string('slack_botname')->nullable();
			$table->string('default_currency', 10)->nullable();
			$table->text('custom_css', 65535)->nullable();
			$table->boolean('brand')->default(1);
			$table->string('ldap_enabled')->nullable();
			$table->string('ldap_server')->nullable();
			$table->string('ldap_uname')->nullable();
			$table->text('ldap_pword')->nullable();
			$table->string('ldap_basedn')->nullable();
			$table->text('ldap_filter', 65535)->nullable();
			$table->string('ldap_username_field')->nullable()->default('samaccountname');
			$table->string('ldap_lname_field')->nullable()->default('sn');
			$table->string('ldap_fname_field')->nullable()->default('givenname');
			$table->string('ldap_auth_filter_query')->nullable()->default('uid=samaccountname');
			$table->integer('ldap_version')->nullable()->default(3);
			$table->string('ldap_active_flag')->nullable();
			$table->string('ldap_emp_num')->nullable();
			$table->string('ldap_email')->nullable();
			$table->boolean('full_multiple_companies_support')->default(0);
			$table->boolean('ldap_server_cert_ignore')->default(0);
			$table->string('locale', 5)->nullable()->default('en');
			$table->boolean('labels_per_page')->default(30);
			$table->decimal('labels_width', 6, 5)->default(2.62500);
			$table->decimal('labels_height', 6, 5)->default(1.00000);
			$table->decimal('labels_pmargin_left', 6, 5)->default(0.21975);
			$table->decimal('labels_pmargin_right', 6, 5)->default(0.21975);
			$table->decimal('labels_pmargin_top', 6, 5)->default(0.50000);
			$table->decimal('labels_pmargin_bottom', 6, 5)->default(0.50000);
			$table->decimal('labels_display_bgutter', 6, 5)->default(0.07000);
			$table->decimal('labels_display_sgutter', 6, 5)->default(0.05000);
			$table->boolean('labels_fontsize')->default(9);
			$table->decimal('labels_pagewidth', 7, 5)->default(8.50000);
			$table->decimal('labels_pageheight', 7, 5)->default(11.00000);
			$table->boolean('labels_display_name')->default(0);
			$table->boolean('labels_display_serial')->default(1);
			$table->boolean('labels_display_tag')->default(1);
			$table->string('alt_barcode')->nullable()->default('C128');
			$table->boolean('alt_barcode_enabled')->nullable()->default(1);
			$table->integer('alert_interval')->nullable()->default(30);
			$table->integer('alert_threshold')->nullable()->default(5);
			$table->string('email_domain')->nullable();
			$table->string('email_format')->nullable()->default('filastname');
			$table->string('username_format')->nullable()->default('filastname');
			$table->boolean('is_ad')->default(0);
			$table->string('ad_domain')->nullable();
			$table->string('ldap_port', 5)->default('389');
			$table->boolean('ldap_tls')->default(0);
			$table->integer('zerofill_count')->default(5);
			$table->boolean('ldap_pw_sync')->default(1);
			$table->boolean('two_factor_enabled')->nullable();
			$table->boolean('require_accept_signature')->default(0);
		});
		
		DB::table('settings')->insert(
			array('id' => '1','created_at' => '2017-04-04 13:15:27','updated_at' => '2017-04-04 13:15:27','user_id' => '1','per_page' => '20','site_name' => 'MMReco','qr_code' => NULL,'qr_text' => NULL,'display_asset_name' => NULL,'display_checkout_date' => NULL,'display_eol' => NULL,'auto_increment_assets' => '0','auto_increment_prefix' => '0','load_remote' => '1','logo' => NULL,'header_color' => NULL,'alert_email' => 'dhanraj@parextech.com','alerts_enabled' => '1','default_eula_text' => NULL,'barcode_type' => 'QRCODE','slack_endpoint' => NULL,'slack_channel' => NULL,'slack_botname' => NULL,'default_currency' => 'INR','custom_css' => NULL,'brand' => '1','ldap_enabled' => NULL,'ldap_server' => NULL,'ldap_uname' => NULL,'ldap_pword' => NULL,'ldap_basedn' => NULL,'ldap_filter' => NULL,'ldap_username_field' => 'samaccountname','ldap_lname_field' => 'sn','ldap_fname_field' => 'givenname','ldap_auth_filter_query' => 'uid=samaccountname','ldap_version' => '3','ldap_active_flag' => NULL,'ldap_emp_num' => NULL,'ldap_email' => NULL,'full_multiple_companies_support' => '0','ldap_server_cert_ignore' => '0','locale' => 'en','labels_per_page' => '30','labels_width' => '2.62500','labels_height' => '1.00000','labels_pmargin_left' => '0.21975','labels_pmargin_right' => '0.21975','labels_pmargin_top' => '0.50000','labels_pmargin_bottom' => '0.50000','labels_display_bgutter' => '0.07000','labels_display_sgutter' => '0.05000','labels_fontsize' => '9','labels_pagewidth' => '8.50000','labels_pageheight' => '11.00000','labels_display_name' => '0','labels_display_serial' => '1','labels_display_tag' => '1','alt_barcode' => 'C128','alt_barcode_enabled' => '1','alert_interval' => '30','alert_threshold' => '5','email_domain' => 'parextech.com','email_format' => 'filastname','username_format' => 'filastname','is_ad' => '0','ad_domain' => NULL,'ldap_port' => '389','ldap_tls' => '0','zerofill_count' => '5','ldap_pw_sync' => '1','two_factor_enabled' => NULL,'require_accept_signature' => '0')
			);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
