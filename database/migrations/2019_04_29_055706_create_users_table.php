<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->nullable();
			$table->string('password');
			$table->text('permissions', 65535)->nullable();
			$table->boolean('activated')->default(0);
			$table->boolean('login_enable')->default(0);
			$table->string('activation_code')->nullable()->index();
			$table->dateTime('activated_at')->nullable();
			$table->dateTime('last_login')->nullable();
			$table->string('persist_code')->nullable();
			$table->string('reset_password_code')->nullable()->index();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('website')->nullable();
			$table->string('country')->nullable();
			$table->string('gravatar')->nullable();
			$table->integer('location_id')->nullable();
			$table->string('phone')->nullable();
			$table->string('jobtitle')->nullable();
			$table->integer('manager_id')->nullable();
			$table->string('employee_num', 20)->nullable();
			$table->string('avatar')->nullable();
			$table->string('username')->nullable();
			$table->text('notes', 65535)->nullable();
			$table->integer('company_id')->unsigned()->nullable();
			$table->text('remember_token', 65535)->nullable();
			$table->boolean('ldap_import')->nullable()->default(0);
			$table->string('locale', 5)->nullable()->default('en');
			$table->boolean('show_in_list')->default(1);
			$table->string('two_factor_secret', 32)->nullable();
			$table->boolean('two_factor_enrolled')->default(0);
			$table->boolean('two_factor_optin')->default(0);
			$table->decimal('salary', 13, 3)->nullable();
			$table->decimal('gross_salary', 13, 3)->nullable();
			$table->decimal('new_gross_salary', 13, 3)->nullable();
			$table->enum('user_role', array('management','head_program','head_service','pm_program','pm_service','user','cost','sales','scm','hod_pm','TTL'))->nullable();
			$table->integer('dept_id')->nullable();
			$table->date('dob')->nullable();
			$table->integer('pin_code');
			$table->decimal('new_salary', 13, 3)->nullable();
			$table->date('new_salary_date')->nullable();
			$table->string('gate_pass', 50)->nullable();
			$table->date('agreement_end_date')->nullable();
			$table->text('certificates', 65535)->nullable();
			$table->string('full_name')->nullable();
			$table->string('address', 200);
			$table->string('address2', 200);
			$table->string('city', 200);
			$table->string('nwm_reference', 200)->nullable();
			$table->string('ni_reference', 200)->nullable();
			$table->string('company', 200)->nullable();
			$table->string('uplifts_t1', 200)->nullable();
			$table->string('uplifts_t2', 200)->nullable();
			$table->string('performance_score', 200)->nullable();
			$table->string('warnings', 200)->nullable();
			$table->string('blacklist', 200)->nullable();
			$table->enum('status', array('Active','In Active'))->default('Active');
		});


		DB::table('users')->insert(
	        array('id' => '1','email' => 'maulik@parextech.com','password' => '$2y$10$bWTTrNNy66xCicJtotKOjOLqgkqDAogWnsKGtICefWzPsjFtPi6Bu','permissions' => '{"admin":"1"}','activated' => '0','login_enable' => '1','activation_code' => NULL,'activated_at' => NULL,'last_login' => NULL,'persist_code' => NULL,'reset_password_code' => NULL,'first_name' => 'maulik','last_name' => 'maulika','created_at' => '2017-06-16 13:02:18','updated_at' => '2019-02-26 18:15:03','deleted_at' => NULL,'website' => '','country' => NULL,'gravatar' => '','location_id' => '1621','phone' => '8160534945','jobtitle' => '','manager_id' => NULL,'employee_num' => '','avatar' => NULL,'username' => 'maulik','notes' => '','company_id' => '1','remember_token' => '4V8L1QZCEyxneIhsuj5OlvbM5AibRNAhyC6vhjYJ2bUfVMdE6xyCSiBDpsuu','ldap_import' => '0','locale' => '','show_in_list' => '1','two_factor_secret' => NULL,'two_factor_enrolled' => '0','two_factor_optin' => '0','salary' => '11.000','gross_salary' => '11.000','new_gross_salary' => NULL,'user_role' => 'management','dept_id' => '0','dob' => '0000-00-00','pin_code' => '0','new_salary' => '0.000','new_salary_date' => NULL,'gate_pass' => '','agreement_end_date' => '0000-00-00','certificates' => '','full_name' => 'maulik maulik','address' => '','address2' => '','city' => '','nwm_reference' => '','ni_reference' => '','company' => '','uplifts_t1' => '','uplifts_t2' => '','performance_score' => '','warnings' => '','blacklist' => '','status' => 'Active')
	    );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
