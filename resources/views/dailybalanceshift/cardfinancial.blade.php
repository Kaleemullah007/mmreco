<table
            name="agencybanking"
            data-height="500"
            data-toolbar="#toolbar"
            class="table table-striped snipe-table1"
            id="table1"
            data-toggle="table"           
            data-url="{{ route('dailybalanceshift/getCardfinancialDatatable',array('dailybalanceshiftId'=>$dailybalanceshiftId , 'dailyBalanceShiftPanNum' => $dailyBalanceShiftPanNum)) }}"
            data-cookie="true"
            data-click-to-select="true"
            data-cookie-id-table="abTableDisplay">
            <thead>
              <tr>
                <th data-sortable="true" data-searchable="true" data-field="actions">Action</th>
                <th data-searchable="true" data-sortable="true" data-field="LocalDate">{{ trans('admin/card/table.financial.local_date') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.financial.settlement_date') }}</th>
                
                <th data-sortable="true" data-searchable="true" data-field="BillAmt_value">{{ trans('admin/card/table.financial.bill_amt_value') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.financial.card_pan') }}</th>   

                <th data-sortable="true" data-searchable="true" data-field="RecordType">{{ trans('admin/card/table.financial.record_type') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="TxnCode_direction">{{ trans('admin/card/table.financial.txn_code_direction') }}</th>
                
                <th data-searchable="true" data-sortable="true" data-field="FinId">{{ trans('admin/card/table.financial.fin_id') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="AuthId">{{ trans('admin/card/table.financial.auth_id') }}</th>
                

                <th data-sortable="true" data-searchable="true" data-field="TxnCode_Type">{{ trans('admin/card/table.financial.txn_code_type') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="TxnCode_ProcCode">{{ trans('admin/card/table.financial.txn_code_proc_code') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="TxnAmt_value">{{ trans('admin/card/table.financial.txn_amt_value') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="CashbackAmt_value">{{ trans('admin/card/table.financial.cashback_amt_value') }}</th>


                <th data-sortable="true" data-searchable="true" data-field="Fee_direction">{{ trans('admin/card/table.financial.fee_direction') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="Fee_value">{{ trans('admin/card/table.financial.fee_value') }}</th>
                
                <th data-sortable="true" data-searchable="true" data-field="SettlementAmt_value">{{ trans('admin/card/table.financial.settlement_amt_value') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.financial.created_at') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.financial.file_date') }}</th>  

                <th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>
                <th data-searchable="true" data-sortable="true" data-field="ApprCode">ApprCode</th>
                <th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>
                <th data-searchable="true" data-sortable="true" data-field="Schema">Schema</th>
                <th data-searchable="true" data-sortable="true" data-field="ARN">ARN</th>
                <th data-searchable="true" data-sortable="true" data-field="FIID">FIID</th>
                <th data-searchable="true" data-sortable="true" data-field="RIID">RIID</th>
                <th data-searchable="true" data-sortable="true" data-field="ReasonCode">ReasonCode</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_programid">Card_programid</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_branchcode">Card_branchcode</th>
                <th data-searchable="true" data-sortable="true" data-field="Account_no">Account_no</th>
                <th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>
                <th data-searchable="true" data-sortable="true" data-field="TxnCode_Group">TxnCode_Group</th>
                <th data-searchable="true" data-sortable="true" data-field="TxnAmt_currency">TxnAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="CashbackAmt_currency">CashbackAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="BillAmt_currency">BillAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="BillAmt_rate">BillAmt_rate</th>
                <th data-searchable="true" data-sortable="true" data-field="Trace_auditno">Trace_auditno</th>
                <th data-searchable="true" data-sortable="true" data-field="Trace_origauditno">Trace_origauditno</th>
                <th data-searchable="true" data-sortable="true" data-field="Trace_Retrefno">Trace_Retrefno</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_code">Term_code</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_location">Term_location</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_street">Term_street</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_city">Term_city</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_country">Term_country</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_inputcapability">Term_inputcapability</th>
                <th data-searchable="true" data-sortable="true" data-field="Term_authcapability">Term_authcapability</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_cardholderpresent">Txn_cardholderpresent</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_cardpresent">Txn_cardpresent</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_cardinputmethod">Txn_cardinputmethod</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_cardauthmethod">Txn_cardauthmethod</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_cardauthentity">Txn_cardauthentity</th>
                <th data-searchable="true" data-sortable="true" data-field="Txn_TVR">Txn_TVR</th>
                <th data-searchable="true" data-sortable="true" data-field="MsgSource_value">MsgSource_value</th>
                <th data-searchable="true" data-sortable="true" data-field="MsgSource_domesticMaestro">MsgSource_domesticMaestro</th>
                <th data-searchable="true" data-sortable="true" data-field="Fee_currency">Fee_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="SettlementAmt_currency">SettlementAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="SettlementAmt_rate">SettlementAmt_rate</th>
                <th data-searchable="true" data-sortable="true" data-field="SettlementAmt_date">SettlementAmt_date</th>
                <th data-searchable="true" data-sortable="true" data-field="Classification_RCC">Classification_RCC</th>
                <th data-searchable="true" data-sortable="true" data-field="Classification_MCC">Classification_MCC</th>
                <th data-searchable="true" data-sortable="true" data-field="Response_approved">Response_approved</th>
                <th data-searchable="true" data-sortable="true" data-field="Response_actioncode">Response_actioncode</th>
                <th data-searchable="true" data-sortable="true" data-field="Response_responsecode">Response_responsecode</th>
                <th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_value">OrigTxnAmt_value</th>
                <th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_currency">OrigTxnAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_origItemId">OrigTxnAmt_origItemId</th>
                <th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_partial">OrigTxnAmt_partial</th>
                <th data-searchable="true" data-sortable="true" data-field="CCAAmount_value">CCAAmount_value</th>
                <th data-searchable="true" data-sortable="true" data-field="CCAAmount_currency">CCAAmount_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="CCAAmount_included">CCAAmount_included</th>             
                
              </tr>
            </thead>            
          </table>