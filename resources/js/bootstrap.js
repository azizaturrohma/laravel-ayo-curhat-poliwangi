import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import "./echo";

// window.Echo = new Echo({
//     broadcaster: "pusher",
//     key: "3093484b130a3658ac13",
//     cluster: "ap1",
//     forceTLS: true,
// });

const options = {
    broadcaster: "pusher",
    key: "35d483317dcf7d8a6aa6",
    cluster: "ap1",
};

// window.Echo = new Echo({
//     client: new Pusher(options.key, options),
// });

var channel = Echo.channel("counseling-messages");
channel.listen(".CounselingMessages", function (data) {
    alert(JSON.stringify(data));
});
