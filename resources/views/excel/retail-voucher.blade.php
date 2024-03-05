<table>
     <thead>
         <tr>
             <td colspan="18" style="border: 2px solid black;  text-align: center;  font-size: 15px;">
                 <center>
                     <h2>
                         Voucher Report
                     </h2>
                 </center>
             </td>
         </tr>
     </thead>
 </table>
 <table>
     <thead>
         <tr style="border: 1px thick #000">
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Voucher</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Store</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Order No.</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Reference Code</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Price </strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Discount Price </strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Customer Name</strong></td>
             <!-- <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Contact No.</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Email</strong></td> -->
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Date Ordered</strong></td>
             <!-- <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Date Cancelled</strong></td> -->
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Order Status</strong></td>
         </tr>
     </thead>
     <tbody>
         @foreach($retails as $retail)
         <tr>
             <td colspan="2" style="text-align: left;">{{ $retail->voucher_code }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->store_name }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->order_number }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->reference_code }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->total_order_price }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->total_discount_price }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->customer_name }}</td>
             <!-- <td colspan="2" style="text-align: left;">{{ $retail->contact_number }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->email }}</td> -->
             <td colspan="2" style="text-align: left;">{{ $retail->created_at }}</td>
             <!-- <td colspan="2" style="text-align: left;">{{ $retail->cancelled_date }}</td> -->
             <td colspan="2" style="text-align: left;">{{ $retail->order_status }}</td>
         </tr>
         @endforeach
     </tbody>
 </table>