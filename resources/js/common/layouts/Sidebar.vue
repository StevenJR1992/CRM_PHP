<template>
    <div
        class="right-sidebar"
        :style="{
            borderLeft: themeMode == 'dark' ? '1px solid #303030' : '1px solid #f0f0f0',
        }"
    >
        <iframe
            src="https://desarrollocr.ucontactcloud.com/uphone/"
            width="100%"
            frameborder="0"
            scrolling="no"
            allow="camera;microphone"
            id="ucontact"
            ref="iframe"
            @load="onIframeLoad"
            style="height: 100vh"
        ></iframe>
    </div>
</template>
<script>
import { defineComponent, onMounted, onUnmounted, ref, computed } from "vue";
import common from "../../common/composable/common";
import { useRoute } from "vue-router";
import { useStore } from "vuex";

export default defineComponent({
    props: {
        leadId: {
            default: "",
        },
    },
    setup(props, { emit }) {
        const { rightSidebarValue, user, themeMode } = common();
        const loading = ref(true);
        const iframe = ref(null);

        const store = useStore();
        const route = useRoute();
        const routePath = computed(() => route.name);

        // Define the event handler
        const handleMessageEvent = (e) => {
            if (e.data.params) {
                axiosAdmin
                    .post("uphone-calls/save", {
                        ...e.data.params,
                        lead_id: props.leadId,
                    })
                    .then((response) => {
                        store.commit(
                            "auth/updateUphoneCallReloadString",
                            Math.random() * 20
                        );
                    });
            }
        };

        onMounted(() => {
            window.addEventListener("message", handleMessageEvent);
            // window.addEventListener("message", receiveMessage, false);
            // iframe.value.addEventListener("load", sendMessage, false);
            // onIframeLoad();
        });

        onUnmounted(() => {
            console.log("unmounted");
            // Remove the event listener to prevent memory leaks
            window.removeEventListener("message", handleMessageEvent);
        });

        // const sendMessage = () => {
        //     const iframe = this.$refs.myIframe;
        //     const message = {
        //         ucontact_user: user.value.ucontact_user,
        //         ucontact_password: user.value.ucontact_password,
        //     };
        //     iframe.contentWindow.postMessage(
        //         message,
        //         "https://desarrollocr.ucontactcloud.com/uphone/"
        //     );
        // };

        // const receiveMessage = (event) => {
        //     if (
        //         event.origin !==
        //         "https://desarrollocr.ucontactcloud.com/uphone/"
        //     ) {
        //         return;
        //     }
        //     console.log("Received message from iframe:", event.data);
        // };

        // iframe window code

        // window.addEventListener(
        //     "message",
        //     function (event) {
        //         // Ensure the message is from the expected origin
        //         if (event.origin !== "http://parent-domain.com") {
        //             return;
        //         }

        //         const credentials = event.data;
        //         if (credentials.token) {
        //             console.log("Received token:", credentials.token);
        //             // Use the token to authenticate requests or perform other actions
        //         }
        //     },
        //     false
        // );

        const onIframeLoad = () => {
            const iframeContent = document.getElementById("ucontact");
            // const iframeDocument =
            //     iframeContent.contentDocument || iframeContent.contentWindow;
            loading.value = false;

            // Assuming you have a form in the iframe that requires credentials
            // var uContactUser = iframeDocument.getElementById("input-10");
            // var uContactPassword = iframeDocument.getElementById("input-12");

            // if (uContactUser && uContactPassword) {
            //     uContactUser.value = user.value.ucontact_user;
            //     uContactPassword.value = user.value.ucontact_password;
            // }
        };

        return { rightSidebarValue, loading, onIframeLoad, iframe, themeMode };
    },
});
</script>

<style lang="less">
.right-sidebar {
    height: calc(100vh);
    position: fixed;
    width: -webkit-fill-available;
}
.loader {
    position: absolute;
    left: 90%;
    top: 32%;
    width: 100px;
    height: 100px;
    margin-left: -50px;
    margin-top: -50px;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

.content {
    position: absolute;
    left: 87.5%;
    top: 40%;
    width: 200px;
    height: 100px;
    // font-weight: bold;
    font-size: 20px;
}

@-webkit-keyframes spin {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    }
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
@media only screen and (max-width: 1150px) {
    .ant-layout-sider.ant-layout-sider-collapsed {
        left: -80px !important;
    }
}
</style>
