<template>
        <div class="content">
			<div class="container-fluid">
                      <h1> I will show how all component react to changes</h1>
                      <!-- <h2>Master component: {{getCounter}} </h2> -->
                    <h2>Master component: {{counter}} </h2>
                      <comA />
                      <comB />
                      <comC />
                    <Button type="info" @click="changeCounter">Change the state of the counter</Button>
            </div>
        </div>
</template>

<script>
import comA from './comA'
import comB from './comB'
import comC from './comC'
import {mapGetters} from 'vuex'

export default {
    data(){
        return {
          
        }
    },

    components : {
        comA,
        comB,
        comC
    }, 
    computed:{
        //...mapGetters(['getCounter'])
                ...mapGetters({
                    'counter' : 'getCounter'
                    })

    },
    methods:{
       changeCounter(){
           this.$store.dispatch('changeCounterAction',1)

           //this.$store.commit('changeTheCounter', 1)
        }, 
    },
    created()
    {
        console.log(this.$store.state.counter);
    },
    watch:{
        counter(value){
            console.log('counter is changing..'+value);
        }
    }
}
</script>

<style>

</style>