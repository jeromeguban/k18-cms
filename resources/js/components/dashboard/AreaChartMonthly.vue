<script>
import { Line } from "vue-chartjs";

export default {
    props: ['orders'],
    extends: Line,
        data() {
            return {
                gradient: null,
                gradient2: null,
                list_orders : [],
            };
        },

        created() {

            this.list_orders = this.orders[0];

        },

        watch: {

            'orders'() {
                this.list_orders = this.orders[0];
            },

        },

        mounted() {
            this.gradient2 = this.$refs.canvas
                .getContext("2d")
                .createLinearGradient(0, 0, 0, 450);

            this.gradient2.addColorStop(0, "rgba(0, 231, 255, 0.9)");
            this.gradient2.addColorStop(0.5, "rgba(0, 231, 255, 0.25)");
            this.gradient2.addColorStop(1, "rgba(0, 231, 255, 0)");

            this.renderChart({
                labels: [
                    "1",
                    "2",
                    "3",
                    "4",
                    "5",
                    "6",
                    "7",
                    "8",
                    "9",
                    "10",
                    "11",
                    "12",
                    "13",
                    "14",
                    "15",
                    "16",
                    "17",
                    "18",
                    "19",
                    "20",
                    "21",
                    "22",
                    "23",
                    "24",
                    "25",
                    "26",
                    "27",
                    "28",
                    "29",
                    "30",
                    "31"
                ],
                datasets: [{
                    label: "Sales",
                    borderColor: "#05CBE1",
                    pointBackgroundColor: "white",
                    pointBorderColor: "white",
                    borderWidth: 1,
                    backgroundColor: this.gradient2,
                    data: [
                        this.list_orders.total_amount_per_day_1,
                        this.list_orders.total_amount_per_day_2,
                        this.list_orders.total_amount_per_day_3,
                        this.list_orders.total_amount_per_day_4,
                        this.list_orders.total_amount_per_day_5,
                        this.list_orders.total_amount_per_day_6,
                        this.list_orders.total_amount_per_day_7,
                        this.list_orders.total_amount_per_day_8,
                        this.list_orders.total_amount_per_day_9,
                        this.list_orders.total_amount_per_day_10,
                        this.list_orders.total_amount_per_day_11,
                        this.list_orders.total_amount_per_day_12,
                        this.list_orders.total_amount_per_day_13,
                        this.list_orders.total_amount_per_day_14,
                        this.list_orders.total_amount_per_day_15,
                        this.list_orders.total_amount_per_day_16,
                        this.list_orders.total_amount_per_day_17,
                        this.list_orders.total_amount_per_day_18,
                        this.list_orders.total_amount_per_day_19,
                        this.list_orders.total_amount_per_day_20,
                        this.list_orders.total_amount_per_day_21,
                        this.list_orders.total_amount_per_day_22,
                        this.list_orders.total_amount_per_day_23,
                        this.list_orders.total_amount_per_day_24,
                        this.list_orders.total_amount_per_day_25,
                        this.list_orders.total_amount_per_day_26,
                        this.list_orders.total_amount_per_day_27,
                        this.list_orders.total_amount_per_day_28,
                        this.list_orders.total_amount_per_day_29,
                        this.list_orders.total_amount_per_day_30,
                        this.list_orders.total_amount_per_day_31
                    ]
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        },

         watch: {
            'orders'() {
                this.list_orders = this.orders[0];

                this.renderChart({
                    labels: [
                        "1",
                        "2",
                        "3",
                        "4",
                        "5",
                        "6",
                        "7",
                        "8",
                        "9",
                        "10",
                        "11",
                        "12",
                        "13",
                        "14",
                        "15",
                        "16",
                        "17",
                        "18",
                        "19",
                        "20",
                        "21",
                        "22",
                        "23",
                        "24",
                        "25",
                        "26",
                        "27",
                        "28",
                        "29",
                        "30",
                        "31"
                    ],
                    datasets: [{
                        label: "Sales",
                        borderColor: "#05CBE1",
                        pointBackgroundColor: "white",
                        pointBorderColor: "white",
                        borderWidth: 1,
                        backgroundColor: this.gradient2,
                        data: [
                            this.list_orders.total_amount_per_day_1,
                            this.list_orders.total_amount_per_day_2,
                            this.list_orders.total_amount_per_day_3,
                            this.list_orders.total_amount_per_day_4,
                            this.list_orders.total_amount_per_day_5,
                            this.list_orders.total_amount_per_day_6,
                            this.list_orders.total_amount_per_day_7,
                            this.list_orders.total_amount_per_day_8,
                            this.list_orders.total_amount_per_day_9,
                            this.list_orders.total_amount_per_day_10,
                            this.list_orders.total_amount_per_day_11,
                            this.list_orders.total_amount_per_day_12,
                            this.list_orders.total_amount_per_day_13,
                            this.list_orders.total_amount_per_day_14,
                            this.list_orders.total_amount_per_day_15,
                            this.list_orders.total_amount_per_day_16,
                            this.list_orders.total_amount_per_day_17,
                            this.list_orders.total_amount_per_day_18,
                            this.list_orders.total_amount_per_day_19,
                            this.list_orders.total_amount_per_day_20,
                            this.list_orders.total_amount_per_day_21,
                            this.list_orders.total_amount_per_day_22,
                            this.list_orders.total_amount_per_day_23,
                            this.list_orders.total_amount_per_day_24,
                            this.list_orders.total_amount_per_day_25,
                            this.list_orders.total_amount_per_day_26,
                            this.list_orders.total_amount_per_day_27,
                            this.list_orders.total_amount_per_day_28,
                            this.list_orders.total_amount_per_day_29,
                            this.list_orders.total_amount_per_day_30,
                            this.list_orders.total_amount_per_day_31,
                        ]
                    }]
                }, {
                    responsive: true,
                    maintainAspectRatio: false
                });
            }
        }

    };
</script>
