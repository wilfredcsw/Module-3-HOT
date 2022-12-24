@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h3 class="card-header">Warning Alert History</h3>
            <div class="card-body d-flex justify-content-center align-items-center" >
                <div class="card" style="width: 100%; height: auto;">
                    <div class="card-header" style= "height: 50px;">
                        <table id="myTablehead" style="width: 100%" >
                            <tr>
                                <td>Alarm Status</td>
                                <td>Date</td>
                            </tr>
                        </table>
                        <br>

                    </div>
                    <div class="card-body">
                        <table id="myTable" style="width: 100%">
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $.getJSON('route/alertroute', function(blocksall){
        //console.log(blocksall.blocks)
        var datas = blocksall.blocks.map(String);
        datas = datas.reverse();
        // console.log(datas)
        var datasx = blocksall.blocks2.map(String);
        datasx = datasx.reverse();
        

        var table = document.getElementById("myTable");
        
        for (let step = 0; step < datas.length; step++) {
            // console.log(datas[step]);
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.innerHTML = datas[step];
            cell2.innerHTML = datasx[step];
        }
    });
    
    Echo.channel('earlyflood').listen('floodevent', (e) => {
        var table = document.getElementById("myTable");
        var row = table.insertRow(0);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        cell1.innerHTML = e['deviceID'];
        cell2.innerHTML = new Date().toLocaleString('en-CA', {hour12: false,});
        document.getElementById("myAlert").innerHTML = 'Alarm Trigger!!';
        document.getElementById("myAlert" ).style.color="red";
    });
</script>
@endsection