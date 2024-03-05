<template>
    <div class="w-full">
        <table-template :link="'/cart/' + $route.params.id + '/items'" :fields="fields" :addNewBtn="false">
            <template slot="label">
                <h4>Cart Items</h4>
            </template>
            <template slot="name" slot-scope="props">
                <span class="font-medium">{{ props.item.name }}</span>
            </template>
        </table-template>
    </div>
</template>
<script>
import TableTemplate from "../../Table";
export default {
    props: {
        posting_inquiry: {
            type: Object,
            default: null
        },
    },
    props: ['posting_inquiry'],
    components: { TableTemplate },
    data() {
        return {
            fields: {
                name               : 'Item Name' ,
                description        : 'Description' ,
                auction_number     : 'Auction Number' ,
                lot_number         : 'Lot No.' ,
                quantity           : 'Quantity' ,
                price              : 'Price' ,
                total_price        : 'Total Price' ,
                expires_at         : 'Expiration Date',
                store_name         : 'Store Name' ,
                store_company_name : 'Company' ,
                category           : 'Category' ,
            },
        }
    },
    methods: {

    }
}
</script>
