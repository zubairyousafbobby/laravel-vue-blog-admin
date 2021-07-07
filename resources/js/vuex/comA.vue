<template>
    <div>
        <h1>I am comp A and the counter is : {{counter}}</h1>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
      data(){
        return {
          
        }
    },
    computed:{
        ...mapGetters({
         'counter' : 'getCounter'
        })
    }
}
</script>