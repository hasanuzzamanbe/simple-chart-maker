<template>
    <div class="chart_maker_welcome_wrapper">

        <div class="chart_maker_welcome">
            <h2>Welcome to Chart Maker</h2>
            <p>Thank you for installing, Enjoy your work with Chart Maker.</p>
            <div class="chart_maker_actions">
                <el-button type="success"
                           @click="create"
                >
                    Create First chart
                </el-button>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "welcome",
        methods: {
            create() {
                this.$emit('create', true);
            }
        }
    }
</script>

<style scoped>

</style>
