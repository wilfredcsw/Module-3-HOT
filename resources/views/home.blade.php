@extends('layouts.app')
@section('content')

<style>
    .grid-container {
        display: grid;
        grid-gap: 10px;
        grid-template-columns: repeat(6, minmax(0, 1fr));
    }

     @media only screen and (max-width: 865px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }

    @media only screen and (max-width: 614px) {
        .grid-container {
            grid-template-columns: 100%;
        }

        .grid-item {
            grid-row-start: initial !important;
            grid-row-end: initial !important;
            grid-column-start: initial !important;
            grid-column-end: initial !important;
        }
    }

    .grid-item {
        width: 100%;
        height: 100%;
    }


    .itemChart1{
        grid-row-start: 1;
        grid-row-end: 1;
        grid-column-start: 2;
        grid-column-end: 6;
    }

    .itemChart2{
        grid-row-start: 2;
        grid-row-end: 2;
        grid-column-start: 2;
        grid-column-end: 6;
    }

    .itemChart3{
        grid-row-start: 3;
        grid-row-end: 3;
        grid-column-start: 2;
        grid-column-end: 6;
    }



</style>

<h1><b>Early Flood Detection System</b></h1>
{{-- charts starts here --}}
<div class="container-fluid">
    <div class="dashboard">
        <div class="grid-container">
        {{-- code starts here --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        {{-- create row --}}
            <div class="grid-item itemChart1">
                <div class="card">
                    <h3 class="card-header">Temperature of Surrounding</h3>
                    <div class="card-body d-flex justify-content-center align-items-center" >
                        <canvas id="chartjs1" class="chartjs" style="position: relative; height:30vh; width:40vw"></canvas>                  
                        <!-- script under here -->
                    </div>
                    <span style="text-align:center" id="temptxt"></span>
                </div>
            </div>
            <div class="grid-item itemChart2">
                <div class="card">
                    <h3 class="card-header">Humidity Level of Surrounding</h3>
                    <div class="card-body d-flex justify-content-center align-items-center" >
                        <canvas id="chartjs2" class="chartjs" style="position: relative; height:30vh; width:40vw"></canvas>  
                        <!-- script under here -->
                    </div>
                    <span style="text-align:center" id="humidtxt"></span>
                </div>
            </div>
            <div class="grid-item itemChart3">
                <div class="card">
                    <h3 class="card-header">Current Water Level</h3>
                    <div class="card-body d-flex justify-content-center align-items-center" >
                        <canvas id="chartjs3" class="chartjs" style="position: relative; height:30vh; width:40vw"></canvas>                           
                        <!-- script under here -->
                    </div>
                    <span style="text-align:center" id="waterlvl"></span>
                </div>
            </div>
        {{-- code ends here --}}
        </div>
    </div>
</div>
{{-- charts ends here --}}
<script>
    setInterval(ajaxCall, 5000);
    function ajaxCall(){
        $.getJSON('/route/temperatureroute', function(blocksall){
            // console.log(blocksall);

            var datas = blocksall.blocks.map(Number);
            datas = datas.reverse();
            console.log(datas)
            var datasx = blocksall.blocks2.map(String);
            datasx = datasx.reverse();

            document.getElementById("temptxt").innerHTML = datas[datas.length-1];

            //Timecheck
            first = new Date(datasx[datasx.length-1]); //get the first date epoch object
            //document.write((first.getTime()/1000)); //get the current epoch value
            second = new Date(); //get the first date epoch object
            //document.write((second.getTime()/1000)); //get the current epoch value
            diff = second.getTime() - first.getTime(); //get the actual epoch value
            //console.log(diff);
            if (diff > 5000){
                document.getElementById("temptxt").style.backgroundColor = "red";
            }
            else{
                document.getElementById("temptxt").style.backgroundColor = "green";
            }

            var chart = new Chart(document.getElementById('chartjs1'), {
                options: {
                    animation: {
                        duration: 10,
                        easing: 'easeOutBounce'
                    }
                },
                type: 'line',
                data: {
                    labels : datasx,
                    datasets: [{
                        label: 'Speed',
                        data: datas,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });
        });

        $.getJSON('/route/humidityroute', function(blocksall){
            var datas = blocksall.blocks.map(Number);
            datas = datas.reverse();
            console.log(datas)
            var datasx = blocksall.blocks2.map(String);
            datasx = datasx.reverse();

            document.getElementById("humidtxt").innerHTML = datas[datas.length-1];

            //Timecheck
            first = new Date(datasx[datasx.length-1]); //get the first date epoch object
            //document.write((first.getTime()/1000)); //get the current epoch value
            second = new Date(); //get the first date epoch object
            //document.write((second.getTime()/1000)); //get the current epoch value
            diff = second.getTime() - first.getTime(); //get the actual epoch value
            //console.log(diff);
            if (diff > 5000){
                document.getElementById("humidtxt").style.backgroundColor = "red";
            }
            else{
                document.getElementById("humidtxt").style.backgroundColor = "green";
            }

            var chart = new Chart(document.getElementById('chartjs2'), {
                options: {
                    animation: {
                        duration: 10,
                        easing: 'easeOutBounce'
                    }
                },
                type: 'line',
                data: {
                    labels : datasx,
                    datasets: [{
                        label: 'Temperature',
                        data: datas,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });
        });

        $.getJSON('/route/waterlevelroute', function(blocksall){
            var datas = blocksall.blocks.map(Number);
            datas = datas.reverse();
            console.log(datas)
            var datasx = blocksall.blocks2.map(String);
            datasx = datasx.reverse();

            document.getElementById("waterlvl").innerHTML = datas[datas.length-1];

            //Timecheck
            first = new Date(datasx[datasx.length-1]); //get the first date epoch object
            //document.write((first.getTime()/1000)); //get the current epoch value
            second = new Date(); //get the first date epoch object
            //document.write((second.getTime()/1000)); //get the current epoch value
            diff = second.getTime() - first.getTime(); //get the actual epoch value
            //console.log(diff);
            if (diff > 5000){
                document.getElementById("waterlvl").style.backgroundColor = "red";
            }
            else{
                document.getElementById("waterlvl").style.backgroundColor = "green";
            }

            var chart = new Chart(document.getElementById('chartjs3'), {
                options: {
                    animation: {
                        duration: 10,
                        easing: 'easeOutBounce'
                    }
                },
                type: 'line',
                data: {
                    labels : datasx,
                    datasets: [{
                        label: 'Pressure',
                        data: datas,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                }
            });
        });
    }
</script>
@endsection
