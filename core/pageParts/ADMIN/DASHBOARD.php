<div id="cpContent">
    <?php 
                        $php_array = $pfunc->getPopProdArray();
                        $js_array = json_encode($php_array[0]);
                        $js_n_array = json_encode($php_array[1]);
                        $UBC_Array = $pfunc->getADMLoginData();
                        $php_array_sd = $pfunc->getSalesData();
   
                        $js_mSales_array = json_encode($php_array_sd[0]);
                ?>
    
<div class="cpTopBar">
    <img src="images/controlPanel.svg" style='left: auto; right: 10px;'/>
    <img src="images/Logo_TXT_001.png"/>
    
</div>
    
<div class="cpTopFuncBar"><button onclick="location.href='index.php';"><img src='images/adm_Exit.svg'/></button></div>
    
<div class="cpLeft">
    <ul>
        <button class='active' onclick="location.href='adm.php?p=dashboard'"><img src='images/adm_DashBoard.svg'/><b>Dashboard</b></button>
        <button onclick="location.href='adm.php?p=manage_users'"><img src='images/adm_ManageUser.svg'/><b>Manage Users</b></button>
        <button onclick="location.href='adm.php?p=brand_products'"><img src='images/adm_ManageProduct.svg'/><b>Brands / Products</b></button>
        <button onclick="location.href='adm.php?p=invoices'"><img src='images/adm_ManageInvoices.svg'/><b>Invoices</b></button>
    </ul>
</div>
    
    
<div class="cpRight">
    <div class='dashStats'>
    <div class='dashStatBox'>
        <img src='images/admUsers.svg' class='sbGreen'/>
        <h4 class="tbGreen">Users</h4>
        <b><?php echo $UBC_Array[3]; ?></b>
        </div>
    <div class='dashStatBox'>
        <h4 class="tbBlue">Total Sales</h4>
        <img src='images/admPaid.svg' class='sbBlue'/>
        <b>$<?php echo number_format($php_array_sd[1], 2); ?></b>
        </div>
    <div class='dashStatBox'>
        <img src='images/admOwed.svg' class='sbRed'/>
        <h4 class="tbRed">Unpaid Total</h4>
        <b>$<?php echo number_format($php_array_sd[2], 2); ?></b>
        </div>
 
    <div class='dashStatBox'>
        <img src='images/admProduct.svg' class='sbPur'/>
        <h4 class="tbPur">Purchased Products</h4>
        <b><?php echo $php_array[2]; ?></b>
    </div>
    </div>
    
    <div class='adm_infoWindow'>
        <div class='adm_infWinFill_base admDTL'><canvas id="pieChart" style='width: 100%; height: 100%;'></canvas></div>
        <div class='adm_infWinFill_base admDLM'><canvas id="userBarChart" style='width: 100%; height: 100%;'></canvas></div>
        
        <div class='adm_infWinFill_base admDTM'><canvas id="salesChart" style='width: 100%; height: 100%;'></canvas>
</div>
        <div class='adm_infWinFill_base admDBM'>
            <h4>Sales Info</h4>
           <?php
             if ($php_array_sd[3] > 0.00){
                 if ($php_array_sd[3] > 100.00){
                 echo "<h1 class='salesUp'>>100%<img src='images/ADM_SalesUp.svg'/></h1>";
                 } else {
                 echo "<h1 class='salesUp'>{$php_array_sd[3]}%<img src='images/ADM_SalesUp.svg'/></h1>";
                 }
             } else {
                  echo "<h1 class='salesDown'><img src='images/ADM_SalesDown.svg'/>{$php_array_sd[3]}%</h1>";
             }
            ?>
        </div>
        <div class='adm_infWinFill_base'>
            <img src='images/admOwed.svg' class='admBackDropIMG'/>
            <center><h3></h3></center>
        </div>
    </div>
    
</div>
    
    <script>
    var randomScalingFactor = function() {
        return 1;
    };

    var config = {
        type: 'pie',
        data: {
            datasets: [{
                <?php echo "data:  ". $js_array . ",\n";?>
              
                backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
                ],
                label: 'Dataset 1'
            }],
            <?php echo "labels:  ". $js_n_array . ",\n"; ?>
        },
        options: {
            responsive: true,
            legend: {
            display: true,
            position: 'right',
                labels: {
                    boxWidth: 10
                }
            },
            title: {
            display: true,
            text: 'Top 5 Popular Products'
            }
        }
    };
    
    var ptx = document.getElementById("pieChart").getContext("2d");
    var myPie = new Chart(ptx, config);

 var btx = document.getElementById("userBarChart");
var barChart = new Chart(btx, {
    type: 'horizontalBar',
    data: {
        <?php 
                
                $js_UBC_Label_array = json_encode($UBC_Array[0]);
                $js_UBC_COLOR_array = json_encode($UBC_Array[1]);
                $js_UBC_DATA_array = json_encode($UBC_Array[2]);
                echo "labels:  ". $js_UBC_Label_array . ",\n";
         ?>
        datasets: [{
            label: 'Logins ',
            <?php echo "data:  ". $js_UBC_DATA_array . ",\n"; ?>
            <?php echo "backgroundColor:  ". $js_UBC_COLOR_array . ",\n"; ?>
            <?php echo "borderColor:  ". $js_UBC_COLOR_array . ",\n"; ?>
            borderWidth: 1
        }]
    },
    options: {
         Axes: {
                display: false
            },
        tooltips: {
						enabled: true,
						mode: 'index',
						intersect: false,
                        displayColors: false,
					},
        title: {
            display: true,
            text: 'Montly User Activity'
            },
        legend: {
            display: false,
            position: 'left',
                labels: {
                    boxWidth: 10
                }
            },
        scales: {
            xAxes: [{
                stacked: true,
                ticks: {
                    beginAtZero:true
                }
            }],
            yAxes: [{
                stacked: true
            }]
        }
    }
});
        
var ctx = document.getElementById("salesChart").getContext('2d');    
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "March", "April", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"],
        datasets: [{
            label: 'Monthly Sales 2017',
             <?php echo "data:  ". $js_mSales_array . ",\n"; ?>
            backgroundColor: [
                'rgba(46,204,113, 0.35)'
            ],
            borderColor: [
                'rgba(46,204,113, 1)'
            ],
            borderWidth: 1,
            lineTension: 0,
            radius: 4
        }]
    },
    options: {
        tooltips: {
						enabled: true,
						mode: 'index',
						intersect: false,
                        displayColors: false,
					},
        title: {
            display: true,
            text: '2017 Monthly Sales'
            },
        legend: {
            display: false,
            position: 'left',
                labels: {
                    boxWidth: 10
                }
            },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
    
<div class="cpFooter"></div>
</div>