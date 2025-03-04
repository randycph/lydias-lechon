    
    @php
        $date = new \Carbon\Carbon(app('request')->input('from'));

        $firstDayNextWeek = $date->startOfWeek()->addDays(7);
       
    @endphp


    <div class="row row-sm">
        <div class="col-md-12 mg-b-5">
            <div class="filter-buttons">
                <div class="d-md-flex bd-highlight">
                    <div class="bd-highlight">
                        <div class="dropdown d-inline">
                            <form id="prev_records">
                                @csrf
                                <input type="text" name="prev_from" id="prev_from" value="{{ $firstDayPrevWeek }}">
                                <button class="btn btn-xs btn-secondary" type="submit">
                                    <i data-feather="arrow-left"></i> Previous Week</button>
                            </form>
                        </div>
                    </div>

                    <div class="ml-auto bd-highlight">
                        @for($i = 0; $i <= 6; $i++)
                            @if($i == 0)
                                {{ date('m/d/Y', strtotime("+$i days",strtotime("this week"))) }}
                            @endif
                            
                            @if($i == 6)
                                - {{ date('m/d/Y', strtotime("+$i days",strtotime("this week"))) }}
                            @endif
                        @endfor
                    </div>
                    <div class="ml-auto bd-highlight">
                        <form id="next_records">
                            @csrf
                            <input type="text" name="next_from" value="{{ $firstDayNextWeek }}">
                            <button class="btn btn-xs btn-secondary" type="submit">
                                Next Weeks<i data-feather="arrow-right"></i></button>
                        </form>  
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Pages -->
        <div class="col-md-12 mg-b-50">
            <div class="card card-dashboard-table">
                <table class="table table-bordered" id="sched_tbl">
                    <thead>
                        <tr>
                        <th>&nbsp;</th>
                        <th colspan="7"><center>Schedules</center></th>
                        </tr>
                        <tr>
                            <th>Branches</th>
                            @php
                            
                            @endphp

                            @for($i = 0; $i <= 6; $i++)
                                <th>{{ date('m/d/Y', strtotime("+$i days",strtotime("this week"))) }}</th> 
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                            <tr>
                                <td>{{$branch->name}}</td>
                                @for($i = 0; $i <= 6; $i++)
                                    @php 
                                        $date = date('Y-m-d', strtotime("+$i days",strtotime("this week")));
                                        $total = \App\EcommerceModel\ProductionOrder::total_order($branch->id,$date);
                                    @endphp
                                    <td>
                                        @if($total > 0)                                           
                                            <a href="{{route('show.orders',[$branch->id,$date]) }}">{{$total}}</a>
                                        @endif
                                    </td> 
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>