@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/banktxn/general.banktxn_import') }}
@parent
@stop

@section('header_right')

@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
        <div class="box-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#auto">Auto Matched</a></li>
            <li><a data-toggle="tab" href="#unmatched">Unmatched</a></li>
            <li><a data-toggle="tab" href="#manually">Manually Matched</a></li>
            <li><a data-toggle="tab" href="#manually-op">Unmatched (optional)</a></li>
          </ul>
          <div class="tab-content">

            <div id="auto" class="tab-pane fade in active">
              <h3>Auto Matched</h3>
              <div class="table table-responsive table-bodered">
                <table class="table table-bodered" style="border: 3px double #3c8dbc;">
                  <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                </table>
              </div>
            </div>

            <div id="unmatched" class="tab-pane fade">
              <div class="col-sm-12">
                <div class="matching-table">
                  <div class="col-sm-6">
                    <h2 class="text-center table-title">Choose Date Here</h2>
                    <div class="fixed-table-toolbar">
                      <div class="bars pull-left"></div>
                      <div class="columns columns-right btn-group pull-right">
                        <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                          <i class="fa fa-refresh"></i>
                        </button>
                        <div class="keep-open btn-group" title="Columns">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-columns"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li>
                              <label>
                                <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="date"> Date
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="desc"> Desc
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="amt"> Amount
                              </label>
                            </li>
                          </ul>
                        </div>
                        <div class="export btn-group">
                          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                            <i class="fa fa-download"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li data-type="excel">
                              <a href="javascript:void(0)">MS-Excel</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="pull-right search">
                        <input class="form-control" type="text" placeholder="Search">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bodered match-table" style="border: 3px double #3c8dbc;">
                        <tr>
                          <th></th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Amount</th>
                        </tr>
                        <tr class="select">
                          <td><input type="checkbox" name="select" checked></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr class="select">
                          <td><input type="checkbox" name="select" checked></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <h2 class="text-center table-title">Compare Data Here</h2>
                    <div class="fixed-table-toolbar">
                      <div class="bars pull-left"></div>
                      <div class="columns columns-right btn-group pull-right">
                        <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                          <i class="fa fa-refresh"></i>
                        </button>
                        <div class="keep-open btn-group" title="Columns">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-columns"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li>
                              <label>
                                <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="date"> Date
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="desc"> Desc
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="amt"> Amount
                              </label>
                            </li>
                          </ul>
                        </div>
                        <div class="export btn-group">
                          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                            <i class="fa fa-download"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li data-type="excel">
                              <a href="javascript:void(0)">MS-Excel</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="pull-right search">
                        <input class="form-control" type="text" placeholder="Search">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bodered match-table" style="border: 3px double #3c8dbc;">
                        <tr>
                          <th></th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Amount</th>
                        </tr>
                        <tr>
                          <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr class="select">
                          <td><input type="checkbox" name="select" checked></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="select"></td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center">
                    <button class="btn btn-primary">Select Matches</button>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="resulting-table">
                  <div class="col-sm-12">
                    <h2 class="text-center table-title">Result</h2>
                    <div class="fixed-table-toolbar">
                      <div class="bars pull-left"></div>
                      <div class="columns columns-right btn-group pull-right">
                        <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                          <i class="fa fa-refresh"></i>
                        </button>
                        <div class="keep-open btn-group" title="Columns">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-columns"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li>
                              <label>
                                <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="date"> Date
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="desc"> Desc
                              </label>
                            </li>
                            <li>
                              <label>
                                <input type="checkbox" data-field="amt"> Amount
                              </label>
                            </li>
                          </ul>
                        </div>
                        <div class="export btn-group">
                          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                            <i class="fa fa-download"></i>
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                            <li data-type="excel">
                              <a href="javascript:void(0)">MS-Excel</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="pull-right search">
                        <input class="form-control" type="text" placeholder="Search">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-bodered result-table" style="border: 3px double #3c8dbc;">
                        <tr>
                          <!-- <th></th> -->
                          <th></th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Amount</th>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Amount</th>
                        </tr>
                        <tr>
                          <td>
                            <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                          </td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td>
                            <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                          </td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                        <tr>
                          <td>
                            <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                          </td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                          <td>17-Aug-2018</td>
                          <td>Test</td>
                          <td>25.00</td>
                        </tr>
                      </table>
                    </div>
                    <a class="btn btn-primary pull-right" href="#manually">Submit Matches</a>
                  </div>
                </div>
              </div>
            </div>

            <div id="manually" class="tab-pane fade">
              <h3>Manually Matched</h3>
              <div class="table-responsive">
                <table class="table table-bodered result-table" style="border: 3px double #3c8dbc;">
                  <tr>
                    <!-- <th></th> -->
                    <th></th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                  </tr>
                  <tr>
                    <td>
                      <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                    </td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>
                      <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                    </td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                  <tr>
                    <td>
                      <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                    </td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                    <td>17-Aug-2018</td>
                    <td>Test</td>
                    <td>25.00</td>
                  </tr>
                </table>
              </div>
              <div class="col-sm-12 text-right">
                <a class="btn btn-primary" href="#manually">Submit</a>
              </div>
            </div>
 
            <div id="manually-op" class="tab-pane fade">

              <div class="flex flex-wrap">

                <div class="matching-table inline-flex flex-wrap">
                  <div class="inline-block w-100 p-y-15">
                    <!-- <div class="col-sm-6"> -->
                      <!-- <div class="row"> -->
                        <div class="inline-block w-100">
                          <h2 class="text-center table-title">Choose Date Here</h2>
                          <div class="fixed-table-toolbar">
                            <div class="bars pull-left"></div>
                            <div class="columns columns-right btn-group pull-right">
                              <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                                <i class="fa fa-refresh"></i>
                              </button>
                              <div class="keep-open btn-group" title="Columns">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-columns"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li>
                                    <label>
                                      <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="date"> Date
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="desc"> Desc
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="amt"> Amount
                                    </label>
                                  </li>
                                </ul>
                              </div>
                              <div class="export btn-group">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                                  <i class="fa fa-download"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li data-type="excel">
                                    <a href="javascript:void(0)">MS-Excel</a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="pull-right search">
                              <input class="form-control" type="text" placeholder="Search">
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bodered match-table" style="border: 2px solid #3c8dbc;">
                              <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                              </tr>
                              <tr class="select">
                                <td><input type="checkbox" name="select" checked></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr class="select">
                                <td><input type="checkbox" name="select" checked></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                            </table>
                          </div>
                          <div class="fixed-table-pagination">
                            <div class="pull-right pagination">
                              <ul class="pagination">
                                <li class="page-first disabled">
                                  <a href="javascript:void(0)">First</a>
                                </li>
                                <li class="page-pre disabled">
                                  <a href="javascript:void(0)">Previous</a>
                                </li>
                                <li class="page-number active">
                                  <a href="javascript:void(0)">1</a>
                                </li>
                                <li class="page-next">
                                  <a href="javascript:void(0)">Next</a>
                                </li>
                                <li class="page-last">
                                  <a href="javascript:void(0)">Last</a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <div class="inline-block w-100" style="border-top: 2px dotted #3c8dbc; margin-top: 10px;">
                          <h2 class="text-center table-title">Compare Data Here</h2>
                          <div class="fixed-table-toolbar">
                            <div class="bars pull-left"></div>
                            <div class="columns columns-right btn-group pull-right">
                              <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                                <i class="fa fa-refresh"></i>
                              </button>
                              <div class="keep-open btn-group" title="Columns">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-columns"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li>
                                    <label>
                                      <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="date"> Date
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="desc"> Desc
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="amt"> Amount
                                    </label>
                                  </li>
                                </ul>
                              </div>
                              <div class="export btn-group">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                                  <i class="fa fa-download"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li data-type="excel">
                                    <a href="javascript:void(0)">MS-Excel</a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="pull-right search">
                              <input class="form-control" type="text" placeholder="Search">
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bodered match-table" style="border: 2px solid #3c8dbc;">
                              <tr>
                                <th></th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr class="select">
                                <td><input type="checkbox" name="select" checked></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="select"></td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                            </table>
                          </div>
                          <div class="fixed-table-pagination">
                            <div class="pull-right pagination">
                              <ul class="pagination">
                                <li class="page-first disabled">
                                  <a href="javascript:void(0)">First</a>
                                </li>
                                <li class="page-pre disabled">
                                  <a href="javascript:void(0)">Previous</a>
                                </li>
                                <li class="page-number active">
                                  <a href="javascript:void(0)">1</a>
                                </li>
                                <li class="page-next">
                                  <a href="javascript:void(0)">Next</a>
                                </li>
                                <li class="page-last">
                                  <a href="javascript:void(0)">Last</a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      <!-- </div> -->
                    <!-- </div> -->
                  </div>
                </div>

                <div class="select-matches-button inline-flex flex-wrap align-item">
                  <div class="inline-block w-100 p-y-15 text-center">
                    <button class="btn btn-primary">
                      <div><i class="fa fa-random fa-2x" aria-hidden="true"></i></div>
                      <div>Select Matches</div>
                    </button>
                  </div>
                </div>

                <div class="resulting-table inline-flex flex-wrap align-self">
                  <div class="inline-block w-100 p-y-15">
                    <!-- <div class="col-sm-6"> -->
                      <!-- <div class="row"> -->
                        <!-- <div class="col-sm-12"> -->
                          <h2 class="text-center table-title">Result Data</h2>
                          <div class="fixed-table-toolbar">
                            <div class="bars pull-left"></div>
                            <div class="columns columns-right btn-group pull-right">
                              <button class="btn btn-default" type="button" name="refresh" title="Refresh">
                                <i class="fa fa-refresh"></i>
                              </button>
                              <div class="keep-open btn-group" title="Columns">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-columns"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li>
                                    <label>
                                      <input type="checkbox" onclick="ckeckAllColumn(this);" data-field="All">Check All
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="date"> Date
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="desc"> Desc
                                    </label>
                                  </li>
                                  <li>
                                    <label>
                                      <input type="checkbox" data-field="amt"> Amount
                                    </label>
                                  </li>
                                </ul>
                              </div>
                              <div class="export btn-group">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                                  <i class="fa fa-download"></i>
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                  <li data-type="excel">
                                    <a href="javascript:void(0)">MS-Excel</a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="pull-right search">
                              <input class="form-control" type="text" placeholder="Search">
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-bodered result-table" style="border: 3px double #3c8dbc;">
                              <tr>
                                <!-- <th></th> -->
                                <th></th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                              </tr>
                              <tr>
                                <td>
                                  <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                                </td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                                </td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                              <tr>
                                <td>
                                  <a class="btn btn-danger btn-sm"><i class="fa fa-close icon-white"></i></a>
                                </td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                                <td>17-Aug-2018</td>
                                <td>Test</td>
                                <td>25.00</td>
                              </tr>
                            </table>
                          </div>
                          <div class="fixed-table-pagination">
                            <div class="pull-right pagination">
                              <ul class="pagination">
                                <li class="page-first disabled">
                                  <a href="javascript:void(0)">First</a>
                                </li>
                                <li class="page-pre disabled">
                                  <a href="javascript:void(0)">Previous</a>
                                </li>
                                <li class="page-number active">
                                  <a href="javascript:void(0)">1</a>
                                </li>
                                <li class="page-next">
                                  <a href="javascript:void(0)">Next</a>
                                </li>
                                <li class="page-last">
                                  <a href="javascript:void(0)">Last</a>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <a class="btn btn-primary pull-right" href="#manually">Submit Matches</a>
                          </div>
                        <!-- </div> -->
                      <!-- </div> -->
                    <!-- </div> -->
                  </div>
                </div>

              </div>

            </div>

          </div>
        </div>
    </div>
  </div>
</div>
@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'users-export', 'search' => true])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<style type="text/css">
  .fixed-table-toolbar {
    display: inline-block;
    width: 100%;
    margin: 10px 0;
  }
</style>
<script>
$(document).ready(function(){

});

</script>
@stop
@stop
