<table
						name="fpout"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table1"
						id="table"
						data-toggle="table"						
						data-url="{{ route('manualtransaction/getFpoutDatatable') }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="fpoutTableDisplay">
						<thead>
							<tr>
								<th data-searchable="false" data-sortable="false" data-field="actions"></th>	

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/fpout/table.file_date') }}</th>	

                                <th data-searchable="true" data-sortable="true" data-field="FPID">{{ trans('admin/fpout/table.fpid') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="BeneficiaryCreditInstitution">{{ trans('admin/fpout/table.beneficiary_credit_institution1') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="BeneficiaryCustomerAccountNumber">{{ trans('admin/fpout/table.beneficiary_customer_account_number1') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="Amount">{{ trans('admin/fpout/table.amount') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="OrigCustomerSortCode">{{ trans('admin/fpout/table.orig_customer_sort_code') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="OrigCustomerAccountNumber">{{ trans('admin/fpout/table.orig_customer_account_number') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="ProcessedAsynchronously">{{ trans('admin/fpout/table.processed_asynchronously') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="ReferenceInformation">{{ trans('admin/fpout/table.reference_information') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="OrigCustomerAccountName">{{ trans('admin/fpout/table.orig_customer_account_name') }}</th>

														
							</tr>
						</thead>						
					</table>