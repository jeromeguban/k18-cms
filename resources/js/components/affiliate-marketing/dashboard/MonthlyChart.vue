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

        watch: {

            'affiliate_marketings'() {
                this.affiliate = this.affiliate_marketings[0];
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
                        this.affiliate.total_amount_as_of_day_1,
                        this.affiliate.total_amount_as_of_day_2,
                        this.affiliate.total_amount_as_of_day_3,
                        this.affiliate.total_amount_as_of_day_4,
                        this.affiliate.total_amount_as_of_day_5,
                        this.affiliate.total_amount_as_of_day_6,
                        this.affiliate.total_amount_as_of_day_7,
                        this.affiliate.total_amount_as_of_day_8,
                        this.affiliate.total_amount_as_of_day_9,
                        this.affiliate.total_amount_as_of_day_10,
                        this.affiliate.total_amount_as_of_day_11,
                        this.affiliate.total_amount_as_of_day_12,
                        this.affiliate.total_amount_as_of_day_13,
                        this.affiliate.total_amount_as_of_day_14,
                        this.affiliate.total_amount_as_of_day_15,
                        this.affiliate.total_amount_as_of_day_16,
                        this.affiliate.total_amount_as_of_day_17,
                        this.affiliate.total_amount_as_of_day_18,
                        this.affiliate.total_amount_as_of_day_19,
                        this.affiliate.total_amount_as_of_day_20,
                        this.affiliate.total_amount_as_of_day_21,
                        this.affiliate.total_amount_as_of_day_22,
                        this.affiliate.total_amount_as_of_day_23,
                        this.affiliate.total_amount_as_of_day_24,
                        this.affiliate.total_amount_as_of_day_25,
                        this.affiliate.total_amount_as_of_day_26,
                        this.affiliate.total_amount_as_of_day_27,
                        this.affiliate.total_amount_as_of_day_28,
                        this.affiliate.total_amount_as_of_day_29,
                        this.affiliate.total_amount_as_of_day_30,
                        this.affiliate.total_amount_as_of_day_31,
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
                            this.affiliate.total_amount_as_of_day_1,
                            this.affiliate.total_amount_as_of_day_2,
                            this.affiliate.total_amount_as_of_day_3,
                            this.affiliate.total_amount_as_of_day_4,
                            this.affiliate.total_amount_as_of_day_5,
                            this.affiliate.total_amount_as_of_day_6,
                            this.affiliate.total_amount_as_of_day_7,
                            this.affiliate.total_amount_as_of_day_8,
                            this.affiliate.total_amount_as_of_day_9,
                            this.affiliate.total_amount_as_of_day_10,
                            this.affiliate.total_amount_as_of_day_11,
                            this.affiliate.total_amount_as_of_day_12,
                            this.affiliate.total_amount_as_of_day_13,
                            this.affiliate.total_amount_as_of_day_14,
                            this.affiliate.total_amount_as_of_day_15,
                            this.affiliate.total_amount_as_of_day_16,
                            this.affiliate.total_amount_as_of_day_17,
                            this.affiliate.total_amount_as_of_day_18,
                            this.affiliate.total_amount_as_of_day_19,
                            this.affiliate.total_amount_as_of_day_20,
                            this.affiliate.total_amount_as_of_day_21,
                            this.affiliate.total_amount_as_of_day_22,
                            this.affiliate.total_amount_as_of_day_23,
                            this.affiliate.total_amount_as_of_day_24,
                            this.affiliate.total_amount_as_of_day_25,
                            this.affiliate.total_amount_as_of_day_26,
                            this.affiliate.total_amount_as_of_day_27,
                            this.affiliate.total_amount_as_of_day_28,
                            this.affiliate.total_amount_as_of_day_29,
                            this.affiliate.total_amount_as_of_day_30,
                            this.affiliate.total_amount_as_of_day_31,
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
