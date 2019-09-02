<template>
    <div class="chart_maker_welcome_wrapper">
        <el-dialog
                title="Add New Chart"
                :visible.sync="centerDialogVisible"
                :before-close="handleClose"
                width="50%"
                right>
            <div>
                <strong>Enter Chart title:</strong>
                <el-input placeholder="Enter your chart name"
                          v-model="chart.chart_title"
                >
                </el-input>
                <div style="margin-top:20px">
                    <strong>Select a chart type:</strong>
                    <el-row>
                        <el-col :span="24">
                            <el-select class="chart_maker_create_modal_sel" v-model="chart.chart_type" placeholder="Select">
                                <el-option
                                        v-for="item in options"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button-group>
                     <el-button type="info" @click="handleClose" >Cancel</el-button>
                     <el-button type="success" @click="createNewChart" >Create Chart</el-button>
                </el-button-group>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "CreateNew",
        props: ['modalVisible',],
        data() {
            return {
                centerDialogVisible: this.modalVisible,
                chart: {
                    chart_title: '',
                    chart_type: ''
                },
                options: [
                    {
                        value: 'pie-donut',
                        label: 'Pie / Donut'
                    }, {
                        value: 'line-chart',
                        label: 'Line Chart'
                    }, {
                        value: 'area-chart',
                        label: 'Area chart'
                    }, {
                        value: 'column-chart',
                        label: 'Column Chart'
                    }, {
                        value: 'bar-chart',
                        label: 'Bar Chart'
                    }
                ]
            }
        },
        methods: {
            createNewChart() {
                    this.submitting = true;
                    // Send Request now
                    this.$adminPost({
                        route: 'create_chart',
                        post_title: this.chart.chart_title,
                        chart_type : this.chart.chart_type
                    })
                        .then(response => {
                            this.$message.success(response.data.message);
                            this.$router.push({name: 'PieAndDonutChart', 
                                               params: {chart_id: response.data.chart.id, 
                                               chart_type: response.data.chart_type}})
                        })
                        .fail(error => {
                            this.$message({
                                message: error.responseJSON.data.message,
                                type: 'error'
                            });
                        })
                        .always(() => {
                            this.submitting = false;
                        });

            },
            handleClose() {
                this.chart.chart_title = '';
                this.chart.chart_type = '';
                this.centerDialogVisible = false;
            }
        },
        watch: {
            centerDialogVisible() {
                this.$emit('update:modalVisible', JSON.parse(this.centerDialogVisible))
            }
        },
    }
</script>

<style scoped>
    .el-select.chart_maker_create_modal_sel input {
        background: none;
        display: block;
    }
    .el-select {
        display: block;
    }

</style>
