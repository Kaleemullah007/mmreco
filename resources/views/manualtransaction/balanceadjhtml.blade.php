<table
						name="fpout"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table1"
						id="table"
						data-toggle="table"						
						data-url="{{ route('manualtransaction/getBalanceAdjDatatable') }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="fpoutTableDisplay">
						<thead>
							<tr>
								<th data-searchable="false" data-sortable="false" data-field="actions"></th>	

                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.baladjust.settlement_date') }}</th>
                                
								<th data-searchable="true" data-sortable="true" data-field="Card_PAN">{{ trans('admin/card/table.baladjust.card_pan') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="Amount_value">{{ trans('admin/card/table.baladjust.amount_value') }}</th>
								
								<th class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/card/table.baladjust.desc') }}</th>
								
								<th data-sortable="true" data-searchable="true" data-field="RecType">{{ trans('admin/card/table.baladjust.rec_type') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="AdjustId">{{ trans('admin/card/table.baladjust.adjust_id') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Account_no">{{ trans('admin/card/table.baladjust.account_no') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Amount_direction">{{ trans('admin/card/table.baladjust.amount_direction') }}</th>	

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.baladjust.file_date') }}</th>		

														
							</tr>
						</thead>						
					</table>