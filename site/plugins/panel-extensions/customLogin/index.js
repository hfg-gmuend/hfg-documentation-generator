panel.plugin("panel-extensions/customLogin", {
  use: [
        (Vue) => {
            Vue.mixin({
                mounted() {
                    // if this function is called but Panel API user login function was already replaced don't replace it again
                    if(Vue.options.mounted === undefined) return;

                    // replace Panel API user login function to use custom login
                    const api = panel.app.$api;

                    api.auth.login = (user) => {
                        let data = {
                            long:     user.remember || false,
                            email:    user.email,
                            password: user.password
                        };

                        return api.post("auth/customLogin", data).then(auth => {
                            return auth.user;
                        });
                    }

                    // remove global mixin from Vue options
                    delete Vue.options.mounted;
                }
            });
        }
    ]
});