<script setup>
import axios from "axios";
import {onMounted , ref} from "vue"
import {useRouter} from "vue-router"

let form = ref ({
    id:""
})
let customers = ref([])
let customer_id = ref([])
const props = defineProps({
    id:{
        type:String,
        default: ''
    }
})
let listCart = ref([]);
let listProducts = ref([]);
const showModel = ref(false);
const hideModel = ref(true);
const router = useRouter();

onMounted (async() => {
    getInvoice(),
    getAllCustomers(),
    getProducts()
})

const getInvoice = async () =>{
    let response=await axios.get(`/api/edit/${props.id}`)

    form.value=response.data.invoice
}
const getAllCustomers = async () => {
        let response = await axios.get('/api/customers')

        customers.value = response.data.customers
    }

    const addCart = (item) => {
        const itemCart ={
            product_id: item.id,
            item_code :item.item_code,
            description : item.description,
            unit_price : item.unit_price,
            quantity : item.quantity
        }

        form.value.invoice_items.push(itemCart)
        
        closeModel()
        // console.log(form.value.invoice_items);
    }
    
    const openModel = () =>{
        showModel.value = !showModel.value
    }

    const closeModel = () =>{
        showModel.value = !hideModel.value
    }


    const getProducts = async () =>{
        let response = await axios.get('/api/products');
        listProducts.value = response.data.products

    }

    const removeItem = (id,i) =>{
        form.value.invoice_items.splice(i,1)
        if(id != undefined){axios.get('/api/delete_invoice_items/'+id)}
    }

    const SubTotal_ =() => {
        let total =0
        if(form.value.invoice_items){
            form.value.invoice_items.map((data)=>{
            total =total + (data.quantity*data.unit_price)
        })

        }
       
        return total ;
    }

    const  Total_ = () => {
        if(form.value.invoice_items){
            return SubTotal_() - form.value.discount
        }
       
    }

    const onEdit = (id) => {
        if(form.value.invoice_items.length>=1){
           
            let subTotal =0
            subTotal = SubTotal_()

            let total  =0 
            total = Total_()
            const formData = new FormData()

            formData.append('invoice_item', JSON.stringify(form.value.invoice_items))
            formData.append('customer_id', form.value.customer_id)
            formData.append('date', form.value.date)
            formData.append('due_date', form.value.due_date)
            formData.append('number', form.value.number)
            formData.append('reference', form.value.reference)
            formData.append('discount', form.value.discount)
            formData.append('subtotal', subTotal)
            formData.append('total',total)
            formData.append('terms_and_conditions', form.value.terms_and_conditions)

            axios.post(`/api/update/${form.value.id}`,formData)

            form.value.invoice_items = []
            
            router.push('/')
        }
    }
</script>

<template>
    <div class="container">

        <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">Edit Invoice</h2>
            </div>
            <div>
                
            </div>
        </div>

        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">Customer</p>
                    <select v-model="form.customer_id" name="" id="" class="input">
                        <option disabled></option>
                        <option :value="customer.id" v-for="customer in customers" :key="customer.id">{{ customer.firstname }}</option>
                    </select>
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <input  v-model="form.date" id="date" placeholder="dd-mm-yyyy" type="date" class="input"> <!---->
                    <p class="my-1">Due Date</p> 
                    <input v-model="form.due_date" id="due_date" type="date" class="input">
                </div>
                <div>
                    <p class="my-1">Numero</p> 
                    <input v-model="form.number" type="text" class="input"> 
                    <p class="my-1">Reference(Optional)</p> 
                    <input v-model="form.reference" type="text" class="input">
                </div>
            </div>
            <br><br>
            <div class="table">

                <div class="table--heading2">
                    <p>Item Description</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                    <p></p>
                </div>
    
                <!-- item 1 -->
                <div class="table--items2" v-for ="(itemCart,i) in form.invoice_items" :key="itemCart.id">
                    <p v-if ="itemCart.product" >#{{ itemCart.product.item_code }} {{ itemCart.product.description }} </p>
                    <p v-else >#{{ itemCart.item_code }} {{ itemCart.description }} </p>
                    <p>
                        <input  v-model="itemCart.unit_price" type="text" class="input" >
                    </p>
                    <p>
                        <input v-model="itemCart.quantity" type="text" class="input" >
                    </p>
                    <p v-if = "itemCart.quantity">
                        $ {{ itemCart.quantity * itemCart.unit_price }}
                    </p>
                    <p v-else></p>
                    <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(itemCart.id,i)">
                        &times;
                    </p>
                </div>

                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="openModel()">Add New Line</button>
                </div>
            </div>

            <div class="table__footer">
                <div class="document-footer" >
                    <p>Terms and Conditions</p>
                    <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                </div>
                <div>
                    <div class="table__footer--subtotal">
                        <p>Sub Total</p>
                        <span>$ {{ SubTotal_() }}</span>
                    </div>
                    <div class="table__footer--discount">
                        <p>Discount</p>
                        <input v-model="form.discount" type="text" class="input">
                    </div>
                    <div class="table__footer--total">
                        <p>Grand Total</p>
                        <span>$ {{ Total_() }}</span>
                    </div>
                </div>
            </div>

           
        </div>
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
                <a @click="onEdit(form.id)" class="btn btn-secondary">
                    Save
                </a>
            </div>
        </div>
        
    </div>
    <!--==================== add modal items ====================-->
    <div class="modal main__modal " :class ="{show : showModel}">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModel()">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <ul style="list-style: none;">
                <li v-for="(item,i) in listProducts" :key="item.id" style="display: grid; grid-template-columns: 30px 350px 15px; align-items: center;padding-bottom: 5px;" >
                    <p>{{ i+1 }}</p>
                    <a href="#">{{ item.item_code }} {{ item.description }}</a>
                    <button @click="addCart(item)" style="border: 1px solid #e0e9f8; height: 34px;width: 35px; cursor: pointer">+</button>

                </li>
                </ul>
            <br><hr>
            <div class="model__footer">
                <button @click="closeModel()" class="btn btn-light mr-2 btn__close--modal">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal ">Save</button>
            </div>
        </div>
    </div>
    
    <br><br><br>
</div>

   
</template>