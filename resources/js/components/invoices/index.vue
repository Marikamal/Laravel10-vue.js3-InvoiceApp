
<template>
    <div class="container">
      <div class="invoices">
        <div class="card__header">
          <div>
            <h2 class="invoice__title">Invoices</h2>
          </div>
          <div>
            <a class="btn btn-secondary" @click="newInvoices_">New Invoice</a>
          </div>
        </div>
        <div class="table card__content">
          <div class="table--filter">
            <span class="table--filter--collapseBtn">
              <i class="fas fa-ellipsis-h"></i>
            </span>
            <div class="table--filter--listWrapper">
              <ul class="table--filter--list">
                <li>
                  <p class="table--filter--link table--filter--link--active">All</p>
                </li>
                <li>
                  <p class="table--filter--link">Paid</p>
                </li>
              </ul>
            </div>
          </div>
          <div class="table--search">
            <div class="table--search--wrapper">
              <select class="table--search--select" name="" id="">
                <option value="">Filter</option>
              </select>
            </div>
            <div class="relative">
              <i class="table--search--input--icon fas fa-search"></i>
              <input v-model="searchInvoice_" @keyup="search_()" class="table--search--input" type="text" placeholder="Search invoice">
            </div>
          </div>
          <div class="table--heading">
            <p>ID</p>
            <p>Date</p>
            <p>Number</p>
            <p>Customer</p>
            <p>Due Date</p>
            <p>Total</p>
          </div>
          <div class="table--items" v-for="item in invoices" :key="item.id" v-if="invoices.length>0">
            <a href="#" class="table--items--transactionId" @click="onshow(item.id)">#{{ item.id }}</a>
            <p>{{ item.date }}</p>
            <p>#{{ item.number }}</p>
            <p v-if="item.customer">{{ item.customer.firstname }}</p>
            <p v-else></p>
            <p>{{ item.due_date }}</p>
            <p> $ {{ item.total }}</p>
          </div>
          <div class="table--items" v-else>
            <p> Invoices not found</p>
          </div>
        </div>
      </div>
    </div>
  </template> 





  


<script setup>
    import {onMounted , ref} from "vue"
    import {useRouter} from "vue-router"

    const router= useRouter();
    let invoices = ref([]);
    let searchInvoice_ = ref([]);

    onMounted(async () =>{
        getInvoices()
    });

    const getInvoices = async () => {
        let response = await axios.get("/api/get_invoices") 
        // console.log('response',response)
        invoices.value = response.data.invoices
}

    const search_ =async () =>{
        let response = await axios.get('/api/searchInvoice_?s=' +searchInvoice_.value);
        invoices.value=response.data.invoices;
    }

    const newInvoices_ = async() =>{
        let form = await axios.get("/api/create_invoices_");
        router.push('/invoice/new')
    }

    const onshow =(id) =>{
        router.push('/invoice/show/'+id)
    }
</script>