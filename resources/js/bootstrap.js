import _ from "lodash";
window._ = _;

import "bootstrap";
import axios from "axios";
import Swal from "sweetalert2";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.Swal = Swal;
