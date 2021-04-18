<template>
    <h4>Your shortcuts</h4>
</template>

<script>

export default {
    methods:{
        data:function(){
            return{
                shortcuts:[],                
            }
        },
        fetchShortcuts(){
            window.axios.get('/widgets/shortcuts').then((response)=>{
                console.log(response);
            })
        }
    }
}
</script>
