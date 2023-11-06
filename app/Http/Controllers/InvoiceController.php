<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use App\Models\InvoiceItem;
class InvoiceController extends Controller
{
    public function getInvoices(){
        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function searchInvoice_(Request $request){
        $search_= $request->get('s');
        if($search_ !=null){
            $invoices=Invoice::with('customer')->where('id','LIKE', "%$search_%")
            ->get();
            return response()->json([
                'invoices' => $invoices
            ], 200);
        }
        else{
            return $this->getInvoices();
        }
       
    }

    public function createInvoices(Request $request){
        
        $counter=Counter::where('key','invoice')->first();
        $random = Counter::where('key','invoices')->first();
        $invoice=Invoice::orderBy('id','DESC')->first();
        if($invoice){
            $invoice=$invoice->id+1;
            $counters=$counter->value + $invoice ;
        }else {
            $counters =$counter->value;
        }

        $formData = [
            'number' =>$counter->prefix.$counters,
            'customer_id'=>null,
            'customer'=>null,
            'date'=>date('y-m-d'),
            'due_date'=>null,
            'reference'=>null,
            'discount'=>0,
            'term_and_conditions'=>'Default Terms',
            'items'=>[
                [
                    'product_id'=>null,
                    'product'=>null,
                    'unit_price'=>0,
                    'quantity'=>1
                ]
            ] 
       ];

       return response()->json($formData);

    }

    public function store(Request $request){

        $invoice = $request->input("invoice_item");
        $invoiceData['subtotal']= $request->input('subtotal');
        $invoiceData['total']= $request->input('total');
        $invoiceData['date']= $request->input('date');
        $invoiceData['customer_id']= $request->input('customer_id');
        $invoiceData['due_date']= $request->input('due_date');
        $invoiceData['number']= $request->input('number');
        $invoiceData['discount']= $request->input('discount');
        $invoiceData['reference']= $request->input('reference');
        $invoiceData['terms_and_conditions']= $request->input('terms_and_conditions');
        // return $invoice;
        $invoiceRecord= Invoice::create($invoiceData);
      $t=json_decode($invoice);
       
        foreach (json_decode($invoice) as $item){
            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoiceRecord->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
          
            InvoiceItem::create ($itemdata);
        }


    }

    public function show($id){
        $invoice = Invoice::with('customer','invoice_items')->find($id);
         return response()->json(['invoice'=>$invoice], 200);
    }

    public function edit($id){

        $invoice = Invoice::with('customer','invoice_items.product')->find($id);
         return response()->json(['invoice'=>$invoice], 200);
    }

    public function delete($id){
        $invoiceRecord=InvoiceItem::findOrFail($id);
        $invoiceRecord->delete();
    }

    public function try(Request $request, $id){

        $invoice= Invoice::where('id',$id)->first();
        $invoice->subtotal = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->due_date = $request->due_date;
        $invoice->date = $request->date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;
        $invoice->update($request->all());

        $invoiceData=$request->input("invoice_item");
        $invoice->invoice_items()->delete();
        // return response()->json(['invoice'=>$invoiceData], 200);

        foreach(json_decode($invoiceData) as $item_){
            // return response()->json(['invoice'=>$item_], 200);
            $itemdata['product_id'] = $item_->id;
            $itemdata['invoice_id'] = $id;
            $itemdata['quantity'] = $item_->quantity;
            $itemdata['unit_price'] = $item_->unit_price;
          
            InvoiceItem::create($itemdata);
        }


         return response()->json(['invoice'=>$invoice], 200);
    }

    public function update (Request $request, $id){
        $invoice= Invoice::where('id',$id)->first();
        $invoice->subtotal = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->due_date = $request->due_date;
        $invoice->date = $request->date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;
        $invoice->update($request->all());

        $invoiceData=$request->input("invoice_item");
        $invoice->invoice_items()->delete();

        foreach(json_decode($invoiceData) as $item_){
            $itemdata['product_id'] = $item_->id;
            $itemdata['invoice_id'] = $id;
            $itemdata['quantity'] = $item_->quantity;
            $itemdata['unit_price'] = $item_->unit_price;
          
            InvoiceItem::create ($itemdata);
        }




    }

    public function deleteInvoice($id){
    $invoiceRecord=Invoice::findOrFail($id);
    $invoiceRecord->invoice_items()->delete();
    $invoiceRecord->delete();
    }
    
}
 