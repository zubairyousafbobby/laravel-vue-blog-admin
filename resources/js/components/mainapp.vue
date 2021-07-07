<template>
<div>
    <div v-if="$store.state.user">
        <!-- <div v-if="isLoggedIn">  -->
        <!--========== ADMIN SIDE MENU one ========-->
        <div class="_1side_menu">
            <div class="_1side_menu_logo">
                <h3 style="text-align: center">Logo Image</h3>
                <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
            </div>

            <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
            <div class="_1side_menu_content">
                <div class="_1side_menu_img_name">
                    <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
                    <p class="_1side_menu_name">Admin</p>
                </div>

                <!--~~~ MENU LIST ~~~~~~-->
                <div class="_1side_menu_list">
                    <ul class="_1side_menu_list_ul">
                        <!-- <li>
                            <router-link to="/"><Icon type="ios-speedometer" /> Home</router-link>
                        </li> -->
                        <li v-for="(menuItem, i) in permission" :key="i" v-if="permission.length && menuItem.read">
                            <span v-if="menuItem.name == '/'"><router-link :to="menuItem.name">    
                                <Icon type="ios-home"/> {{menuItem.resourceName}}</router-link>
                            </span>
                            <span v-else><router-link :to="'/'+menuItem.name">    
                                <Icon type="ios-speedometer"/> {{menuItem.resourceName}}</router-link>
                            </span>
                        </li>
                          <li>
                            <a href="/logout">
                                <Icon type="ios-close-circle" /> Logout</a>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>
        <!--========== ADMIN SIDE MENU ========-->

        <!--========= HEADER ==========-->
        <div class="header">
            <div class="_2menu _box_shadow">
                <div class="_2menu_logo">
                    <ul class="open_button">
                        <li>
                            <Icon type="ios-list" />
                        </li>
                        <!--<li><Icon type="ios-albums" /></li>-->
                    </ul>
                </div>
            </div>
        </div>
        <!--========= HEADER ==========-->
    </div>
    <router-view />
</div>
</template>

<script>
export default {
    props: ["user", "permission"],

    data() {
        return {
            isLoggedIn: false,
        };
    },

    created() {
        this.$store.commit("setUpdateUser", this.user);
        this.$store.commit("setUserPermission",this.permission);
        // console.log(this.permission);
    },
};
</script>
