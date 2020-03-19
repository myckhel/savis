import Http from '../util/Http'
// import {_token} from '../Constants/defaultValues';
// import
export default class {
  constructor() {

  }

  static getCurrentUser = async () => {
    return Http.get('/api/users/current')
    // $.get('/api/users/current')
    // .done((res) => {
    //   if (true) {
    //
    //   } else {
    //
    //   }
    //   console.log(res);
    // })
    // .fail((err) => {
    //   console.log(err);
    // })
  }

}
