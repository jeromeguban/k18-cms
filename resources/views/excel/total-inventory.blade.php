 <table>
     <thead>
         <tr>
             <td colspan="20" style="border: 2px solid black;  text-align: center;  font-size: 15px; ">
                 <center>
                     <h2>
                         Total Inventory Report
                     </h2>
                 </center>
             </td>
         </tr>
     </thead>
 </table>
 <table>
     <thead>
         <tr style="border: 1px thick #000">
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Date</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Branch</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Listers Name</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Product Name</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Total Quantity</strong></td>
             <td colspan="2" style="border: 1px solid black;  text-align: left;"><strong>Total Amount</strong></td>
         </tr>
     </thead>
     <tbody>
         @foreach($retails as $retail)
         <tr>
             <td colspan="2" style="text-align: left;">{{ $retail->updated_at }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->store_name }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->listers_name }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->name }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->first_quantity_log }}</td>
             <td colspan="2" style="text-align: left;">{{ $retail->first_total_amount }}</td>
         </tr>
         @endforeach
     </tbody>
 </table>