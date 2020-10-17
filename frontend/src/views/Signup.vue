<template>
    <div class="box">
        <div class="mask" @click="CloseWindow"></div>
        <v-card width="520" height="440" class="card">
            <v-card-title>
                會員註冊
                <v-spacer></v-spacer>
                <v-btn fab class="close_btn" dark color="red darken-1" @click="CloseWindow">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-divider></v-divider>
            <v-container>
                <v-form ref="form">
                    <v-text-field v-model="name" :counter="10" label="帳號名稱" required></v-text-field>
                    <v-text-field v-model="email" label="E-mail" required></v-text-field>
                    <v-text-field v-model="password" :counter="10" label="密碼" required type="password"></v-text-field>
                    <v-text-field v-model="name" :counter="10" label="連絡電話" required></v-text-field>
                    <div class="btn_area">
                        <v-btn width="96" :loading="loading" :disabled="loading" class="btns" color="blue darken-1" dark> 註冊 </v-btn>
                    </div>
                </v-form>
            </v-container>
        </v-card>
    </div>
</template>

<script>
export default {
    data(){
        return{
            name: "",
            email: "",
            password: "",
            checkbox: false,
            loading: false,
        }
    },
    methods: {
        CloseWindow(){
            this.$emit('close');
        }
    },
    props: {
        open: Boolean
    }
}
</script>

<style lang="scss" scoped>
.box{
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 10;
    .mask {
        background-color: gray;
        opacity: 0.5;
        width: 100vw;
        height: 100vh;
    }
    .card {
        position: fixed;
        z-index: 11;
        .close_btn {
            width: 28px;
            height: 28px;
        }
    }
}
.btns{
    float: right;
}
.btn_area{
    margin-top: 28px;
}
</style>