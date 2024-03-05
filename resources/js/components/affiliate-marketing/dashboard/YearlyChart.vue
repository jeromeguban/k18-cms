<script>
import { Line } from "vue-chartjs";

export default {
    props: ['affiliate_marketings'],
    extends: Line,
        data() {
            return {
                gradient: null,
                gradient2: null,
                affiliate : [],
            };
        },

        created() {

            this.affiliate = this.affiliate_marketings;
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
                        this.affiliate.january_total_amount,
                        this.affiliate.february_total_amount,
                        this.affiliate.march_total_amount,
                        this.affiliate.april_total_amount,
                        this.affiliate.may_total_amount,
                        this.affiliate.june_total_amount,
                        this.affiliate.july_total_amount,
                        this.affiliate.august_total_amount,
                        this.affiliate.september_total_amount,
                        this.affiliate.october_total_amount,
                        this.affiliate.november_total_amount,
                        this.affiliate.december_total_amount,
                    ]
                }]
            }, {
                responsive: true,
                maintainAspectRatio: false
            });
        },

        watch: {
            'affiliate_marketings'() {
                this.affiliate = this.affiliate_marketings[0];

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
                            this.affiliate.january_total_amount,
                            this.affiliate.february_total_amount,
                            this.affiliate.march_total_amount,
                            this.affiliate.april_total_amount,
                            this.affiliate.may_total_amount,
                            this.affiliate.june_total_amount,
                            this.affiliate.july_total_amount,
                            this.affiliate.august_total_amount,
                            this.affiliate.september_total_amount,
                            this.affiliate.october_total_amount,
                            this.affiliate.november_total_amount,
                            this.affiliate.december_total_amount,
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
