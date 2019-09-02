<template>
    <div class="chart_maker_main_app_wraper">
      <welcome v-if="charts.length === 0"
               @create="createFormModal = true"
      />
        <div class="all_chart_maker_wrapper chart_maker_section" v-else>
            <div class="chart_maker_section_header all_payment_chart_wrapper">
                <h1 class="chart_maker_section_title">
                    <!--{{ $t('All Charts') }}-->
                    All Charts
                </h1>
                <div class="chart_maker_section_actions">
                    <div class="chart_maker_action" style="max-width :278px;" search_action>
                        <el-input @keyup.enter.native="fetchCharts()" size="small" placeholder="Search" v-model="search_string" class="input-with-select">
                            <el-button @click="fetchCharts()" slot="append" icon="el-icon-search"></el-button>
                        </el-input>
                    </div>
                    <el-button
                            style="float: right;
                                   margin-bottom: 23px;"
                            @click="createFormModal = true"
                            class="chart_maker_action"
                            size="small"
                            type="primary">
                        <!--{{ $t( 'Add New Chart' ) }}-->
                        Add New Chart
                    </el-button>
                </div>
            </div>
            <div v-loading.fullscreen.lock="duplicatingChart" element-loading-text="Duplicating the chart.. Please wait..." class="chart_maker_section_body">
                <el-table
                        class="chart_maker_tables"
                        v-loading.body="loading"
                        :data="charts"
                        border>

                    <el-table-column :label="ID" width="70">
                        <template slot-scope="scope">
                            <router-link :to="{ name: 'PieAndDonutChart', params: { chart_id: scope.row.ID , chart_type: scope.row.post_excerpt } }">
                            {{ scope.row.ID }}
                            </router-link>
                        </template>
                    </el-table-column>

                    <el-table-column
                            min-width="200"
                            :label="Title">
                        <template slot-scope="scope">
                            <strong>
                                {{ scope.row.post_title }}
                            </strong>
                            <div class="row-actions">
                                <a @click.prevent="editChart(scope.row)" href="#">
                                    Edit Chart
                                </a>
                                |
                                <a @click.prevent="confirmDeleteChart(scope.row)" href="#">
                                    Delete
                                </a>
                                |
                                <a href="#" @click.prevent="duplicateChart(scope.row.ID)">
                                    Duplicate Chart
                                </a>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Chart Type" width="120">
                        <template slot-scope="scope">

                                {{scope.row.post_excerpt}}

                        </template>
                    </el-table-column>
                    <el-table-column label="Status" width="120">
                        <template slot-scope="scope">
                            {{scope.row.post_status}}
                        </template>
                    </el-table-column>
                    <el-table-column label="Create Date" width="120">
                        <template slot-scope="scope">
                            {{scope.row.post_date_gmt}}
                        </template>
                    </el-table-column>
                    <el-table-column width="250" :label="ShortCode">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark"
                                        content="Click to copy shortcode"
                                        title="Click to copy shortcode"
                                        placement="top">
                                <code class="copy"
                                      :data-clipboard-text='`[chartmaker id="${scope.row.ID}"]`'>
                                    <i class="el-icon-document"></i> [chartmaker id="{{ scope.row.ID }}"]
                                </code>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="chart_maker_pagination">
                    <el-pagination
                            background
                            @size-change="handleSizeChange"
                            @current-change="handleCurrentChange"
                            :current-page="page_number"
                            :page-size="per_page"
                            :page-sizes="pageSizes"
                            layout="total, sizes, prev, pager, next"
                            :total="total">
                    </el-pagination>
                </div>
            </div>
        </div>
        <create-new :modalVisible.sync="createFormModal"
            v-if="createFormModal"
        />
    </div>
</template>
<script>
    import Welcome from '../Common/welcome'
    import CreateNew from "../Charts/CreateNew";
    import Clipboard from 'clipboard';
export default {
    name: 'Charts',
    components:{
        Welcome,
        CreateNew
    },
    data() {
        return {
            charts : [],
            createFormModal: false,
            hasCharts: 0,
            per_page: 20,
            page_number: 1,
            search_string: '',
            total: 0,
            loading: false,
            deleteingChart: {},
            pageSizes: [10, 20, 30, 40, 50, 100, 200],
            charts_count: parseInt(window.chartMakerAdmin.charts_count),
            duplicatingChart: false,
            loadingDemoCharts: false,
            ShortCode: 'Shortcode',
            ID:'ID',
            Title: 'Chart Name'

        }
    },
    methods: {
        fetchCharts() {
            this.loading = true;
            this.$adminGet({
                route: 'get_charts',
                per_page: this.per_page,
                page_number: this.page_number,
                search_string: this.search_string
            })
                .then(response => {
                    this.charts = response.data.charts;
                    this.hasCharts = !!response.data.total;
                    this.total = response.data.total;
                })
                .fail(error => {
                    this.$showAjaxError(error);
                })
                .always(() => {
                    this.loading = false;
                });
        },
        confirmDeleteChart(chart) {
            this.deleteingChart = chart;
            this.$confirm(`This will permanently delete Chart: ${chart.post_title}, Id: ${chart.ID}
             Continue?`, 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                this.deleteChartNow();
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Delete canceled'
                });
            });

        },
        editChart(chart) {
            this.$router.push({name: 'PieAndDonutChart', params: { chart_id: chart.ID , chart_type: chart.post_excerpt } })
        },
        deleteChartNow() {
            this.$adminPost({
                action: 'chart_ninja_admin_ajax',
                route: 'delete_chart',
                chart_id: this.deleteingChart.ID
            })
                .then(response => {
                    this.$message.success({
                        message: response.data.message
                    });
                    this.fetchCharts();
                })
                .fail(error => {
                    this.$message.error({
                        message: error.responseJSON.data.message
                    });
                })
                .always(() => {
                    this.deleteDialogVisible = false;
                    this.deleteingChart = {};
                });
        },
        handleDeleteClose() {
            this.this.deleteingChart = {};
        },
        handleCurrentChange(val) {
            this.page_number = val;
            this.fetchCharts();
        },
        handleSizeChange(val) {
            this.per_page = val;
            this.fetchCharts();
        },
        duplicateChart(chartId) {
            this.duplicatingChart = true;
            this.$post({
                action: 'chart_maker_admin_ajax',
                route: 'duplicate_chart',
                chart_id: chartId
            })
                .then(response => {
                    if(response.data.chart.ID) {
                        this.$notify.success(response.data.message);
                        this.fetchCharts();
                        this.$router.push({
                            name: 'PieAndDonutChart',
                            params: {
                                chart_id: response.data.chart.ID,
                                chart_type: response.data.chart.post_excerpt
                            }
                        });
                    } else {
                        this.$notify.error('Something is wrong! Please try again');
                    }
                })
                .fail((error) => {
                    this.$showAjaxError(error);
                })
                .always(() => {
                    this.duplicatingChart = false;
                });
        }
    },
    mounted() {
        this.fetchCharts();
        var clipboard = new Clipboard('.copy');
        clipboard.on('success', (e) => {
            this.$message({
                message: 'Copied to Clipboard!',
                type: 'success'
            });
        });

        window.ChartMakerBus.$emit('site_title', 'All Charts');
    }
}
</script>

<style lang="scss">
    .chart_maker_main_app_wraper {
        .chart_maker_welcome {
            font-family: cursive;
            font-size:33px;
            text-align: center;
            background: #f5f5dc5e;
            padding: 112px 0px 112px 0px;
            max-width: 620px;
            margin: auto;
            margin-top: 20px;
            .chart_maker_actions {
                button {
                    box-shadow: 2px 3px 10px black;
                    background: black;
                    font-size:23px;
                }
            }
        }
        .el-select.chart_maker_create_modal_sel input {
            background: none;
        }
    }
</style>

