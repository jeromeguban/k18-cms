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

        mounted() {
            this.gradient2 = this.$refs.canvas
                .getContext("2d")
                .createLinearGradient(0, 0, 0, 450);

            this.gradient2.addColorStop(0, "rgba(0, 231, 255, 0.9)");
            this.gradient2.addColorStop(0.5, "rgba(0, 231, 255, 0.25)");
            this.gradient2.addColorStop(1, "rgba(0, 231, 255, 0)");

            this.renderChart({
                labels: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                datasets: [{
                    label: "Sales",
                    borderColor: "#05CBE1",
                    pointBackgroundColor: "white",
                    pointBorderColor: "white",
                    borderWidth: 1,
                    backgroundColor: this.gradient2,
                    data: [
                        this.list_orders.total_amount_monthly_january,
                        this.list_orders.total_amount_monthly_fabruary,
                        this.list_orders.total_amount_monthly_march,
                        this.list_orders.total_amount_monthly_april,
                        this.list_orders.total_amount_monthly_may,
                        this.list_orders.total_amount_monthly_june,
                        this.list_orders.total_amount_monthly_july,
                        this.list_orders.total_amount_monthly_august,
                        this.list_orders.total_amount_monthly_september,
                        this.list_orders.total_amount_monthly_october,
                        this.list_orders.total_amount_monthly_november,
                        this.list_orders.total_amount_monthly_december,
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
                        "anuary",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December"
                    ],
                    datasets: [{
                        label: "Sales",
                        borderColor: "#05CBE1",
                        pointBackgroundColor: "white",
                        pointBorderColor: "white",
                        borderWidth: 1,
                        backgroundColor: this.gradient2,
                        data: [
                            this.list_orders.month_total_amount_1,
                            this.list_orders.month_total_amount_2,
                            this.list_orders.month_total_amount_3,
                            this.list_orders.month_total_amount_4,
                            this.list_orders.month_total_amount_5,
                            this.list_orders.month_total_amount_6,
                            this.list_orders.month_total_amount_7,
                            this.list_orders.month_total_amount_8,
                            this.list_orders.month_total_amount_9,
                            this.list_orders.month_total_amount_10,
                            this.list_orders.month_total_amount_11,
                            this.list_orders.month_total_amount_12,
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
