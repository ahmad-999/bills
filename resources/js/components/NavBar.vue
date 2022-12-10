<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="#">NavBar</b-navbar-brand>
            <div v-if="routeName!= 'صفحة العمليات اليومية'"
                style="margin-left: 15%; max-width: fit-content;cursor: pointer;" class="h5"
                @click="()=> $router.push({name:'home-page'})">Home</div>
            <div style="margin-left: 15%; max-width: fit-content;direction: rtl;" class="h6">
                {{ routeName }}
            </div>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>


                <!-- Right aligned nav items -->
                <b-navbar-nav class="ms-auto" style="margin-right: 8rem">
                    <b-nav-form @submit.prevent>
                        <b-form-input @change="onSearchValueChange" v-model="value" autocomplete="off" size="sm"
                            class="me-sm-2" placeholder="Search"></b-form-input>
                    </b-nav-form>

                    <b-button right size="sm" class="my-2 my-sm-0" @click.prevent="signout">Sign out</b-button>

                    <!-- <b-nav-item-dropdown right>
                        <template #button-content>
                            <em>User</em>
                        </template>
                        <b-dropdown-item @click="signout">Sign Out</b-dropdown-item>
                    </b-nav-item-dropdown> -->
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
export default {
    props:['name','cName'],
    mounted(){
        let route = this.$props.name;
        this.routeNameChanger(route);
    },
    data() {
        return {
            value: "",
            routeName: 'صفحة العمليات اليومية',
        }
    },
    watch: {
        name: function (to, from) {
            console.log(to);
            console.log(from);
            this.routeNameChanger(to)
        }
    },
    methods: {
        signout() {
            axios.post('/admin/logout')
                .then((result) => {
                    var msg = result.data.msg;
                    this.$toast.success(msg);
                    localStorage.removeItem('bill-user-token');
                    this.$router.push({ name: 'login' });
                }).catch((err) => {
                    this.$toast.error('something went wrong.');
                });

        },
        onSearchValueChange() {
            this.$emit('onChange', this.value);
        },
        routeNameChanger(to) {
            if (to == 'home')
                this.routeName = 'صفحة العمليات اليومية'
            else if (to == 'customer') {
                let name = this.cName.name;
                this.routeName = `صفحة العمليات الخاصة بالزبون ${name}`
            }
        }
    }

}
</script>

<style scoped>
.form-inline {
    display: flex;
}
</style>