<template>
    <div class="chart_maker_main_builder">
        <el-container>
            <el-header>
                <el-row :gutter="20" style="margin:18px 0px 20px 0px;">
                    <div>
                        <span>
                            <el-button
                                    style="display:inline;
                                    padding: 4px;"
                                    size="mini"
                            >
                                <i class="el-icon-edit"></i> Edit
                        </el-button>
                        <h2  style="display:inline;margin-left: 23px;">{{chart_name}}</h2>
                        </span>
                    </div>
                    <div>
                        <el-button-group style="float:right;">
                            <el-button type="primary" size="mini" @click="update">Update Changes</el-button>
                            <el-button type="info" size="mini" @click="preview">Preview Changes</el-button>
                        </el-button-group>
                    </div>
                </el-row>

            </el-header>
            <el-main>
                <div class="chart_maker_builder">
                    <el-tabs>
                        <el-tab-pane label="Chart data">
                            <div class="1st_tab">
                                <span class="demo-input-label"><h4>Set your chart size:</h4></span>
                                <el-input
                                        placeholder="Please input chart width"
                                        v-model="options.chart.width"
                                        type="number"
                                        size="mini"
                                        min="300"
                                        max="600"
                                        style="max-width:330px"
                                >
                                </el-input>
                                <span class="demo-input-label"><h4>Add labels with data</h4></span>
                                <div class="input_groups"
                                     v-for="(item,i) in chartData.statistics"
                                     :key="i">
                                    <el-row>
                                        <el-col :span="10">
                                            <div class="grid-content bg-purple">
                                                <el-input
                                                        placeholder="Please input a label"
                                                        v-model="item.lable"
                                                        size="mini"
                                                >
                                                </el-input>
                                            </div>
                                        </el-col>
                                        <el-col :span="10">
                                            <div class="grid-content bg-purple-light">
                                                <el-input
                                                        placeholder="Please input value"
                                                        v-model="item.value"
                                                        type="number"
                                                        size="mini"
                                                >
                                                </el-input>
                                            </div>
                                        </el-col>
                                        <el-col :span="4">
                                            <div class="grid-content bg-purple-light">
                                                <el-button
                                                        style="background: none;border: none;"
                                                        size="mini"
                                                        @click="deleteValue(i)"
                                                        icon="el-icon-delete">
                                                </el-button>
                                            </div>
                                        </el-col>
                                    </el-row>
                                </div>
                                <el-row>
                                    <el-col style="text-align: center; margin: 3px 0px 6px 0px ;">
                                        <el-button style="margin-left: -63px;"
                                                   @click="addMore" circle
                                        ><i class="el-icon-plus"
                                        /></el-button>
                                    </el-col>
                                </el-row>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Settings">
                            <div class="second_tab">
                                <!--Chart type selection-->
                                <el-row>
                                    <span class="demo-input-label"><strong>Set Your Chart type: </strong></span>
                                    <el-select v-model="options.chart.type" placeholder="Select" size="mini">
                                        <el-option
                                                v-for="item in chartData.chartType"
                                                :key="item.value"
                                                :label="item.lable"
                                                :value="item.value"
                                        >
                                        </el-option>
                                    </el-select>
                                </el-row>

                                <!--fill colour setting-->
                                <el-row>
                                    <span class="demo-input-label"><strong>Set chart fill colour type: </strong></span>
                                    <el-select v-model="options.fill.type" placeholder="Select" size="mini">
                                        <el-option
                                                v-for="item in fillType"
                                                :key="item.value"
                                                :label="item.lable"
                                                :value="item.value"
                                        >
                                        </el-option>
                                    </el-select>
                                </el-row>

                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
                <div class="chart_maker_main_render">
                    <div id="chart">
                    </div>
                </div>
            </el-main>
        </el-container>
    </div>
</template>

<script>

    import ApexCharts from 'apexcharts'

    export default {
        name: 'PieAndDonutChart',
        data(){
            return {
                chart_name: 'CHART NAME',
                loading: false,
                chartData: {
                    chartType: [{
                        lable: 'Pie',
                        value: 'pie'
                    },
                        {
                            lable: 'Donut',
                            value: 'donut'
                        }
                    ],
                    statistics: [
                        //example data
                        // {
                        //     lable: 'Team A',
                        //     value: 44
                        // }
                    ]
                },
                fillType: [{
                    lable: 'None',
                    value: 'none'
                },
                    {
                        lable: 'Pattern',
                        value: 'pattern'
                    },
                    {
                        lable: 'Gradient',
                        value: 'gradient'
                    },
                ],

                //chart option for rendering
                options : {
                    chart: {
                        width: 600,
                        type: 'donut',
                    },
                    labels: [],
                    series: [],
                    fill: {
                        type: 'pattern',
                        opacity: 3,
                        pattern: {
                            enabled: true,
                            style: ['verticalLines', 'squares', 'horizontalLines', 'circles', 'slantedLines'],
                        },
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                }
            }
        },

        methods: {
            createChart() {
                var chart = new ApexCharts(document.querySelector("#chart"), this.options
                );
                chart.render();
            },
            deleteValue(i) {
                this.chartData.statistics.splice(i,1);
            },
            addMore() {
                this.chartData.statistics.push({
                    lable: '',
                    value: null
                })
            },
            preview(){
                if(this.options.chart.width > 299 && this.options.chart.width< 601) {
                    this.options.labels =[];
                    this.options.series = [];

                    this.chartData.statistics.forEach(element => {
                        this.options.labels.push(element.lable);
                        this.options.series.push(Number(element.value));
                    });
                    this.loading =true
                    var self =this
                    setTimeout(function(){
                        self.createChart();
                        self.$message({
                            message: 'success, Chart render succesfully',
                            type: 'success'
                        });
                    }, 500);
                    this.loading =false;
                    this.createChart();
                }else{
                    this.$message('please use min 300 and max width 600');
                }

            },
            update() {
                this.loading = true;
                this.preview();
                this.$adminPost({
                    route: 'update_chart',
                    chart_id : this.$route.params.chart_id,
                    chart_values: this.options,
                })
                    .then(response => {
                        this.$message.success(response.data.message);
                        console.log(response)
                    })
                    .fail(error => {
                        this.$message({
                            message: error.responseJSON.data.message,
                            type: 'error'
                        });
                    })
                    .always(() => {
                        this.loading = false;
                    });
            },
            getChart(id){
                this.$adminGet({
                    route: 'get_chart_option',
                    chart_id : id
                }).then(res=>{
                    this.chart_name =res.data.chart_name ;
                    if( res.data.chart_values) {
                        this.options.chart.type = res.data.chart_values.chart.type
                        this.options.chart.width = res.data.chart_values.chart.width
                        this.options.fill = res.data.chart_values.fill
                        this.options.labels = res.data.chart_values.labels
                        this.options.series = res.data.chart_values.series
                        this.options.responsive = res.data.chart_values.responsive
                        this.options.labels.forEach((data,i)=>{
                            this.chartData.statistics.push(
                                {   lable: data,
                                    value: this.options.series[i]
                                }
                            )
                        })
                        this.preview();
                    }
                })
            }
        },
        mounted() {
            this.getChart(this.$route.params.chart_id)

        }
    }
</script>
<style lang="scss">
    .chart_maker_main_render {
        float: right;
    }
    .chart_maker_builder {
        float: left;
        min-height:400px;
        min-width:400px;
    }
    .chart_maker_main_builder {
        .second_tab {
            .el-select{
                float: right;
            }
            .el-row {
                margin: 6px 0px 6px 0px;
            }
        }
    }
</style>
