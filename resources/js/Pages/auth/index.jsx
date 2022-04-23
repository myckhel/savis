import { useState } from 'react'
import ForgetPassword from '../../components/auth/ForgetPassword'
import SignIn from '../../components/auth/SignIn'
import SignUp from '../../components/auth/SignUp'
import logoFull from '../../../assets/images/logo-full.png'
import { Pic1 } from '../../../assets/images'

const Auth = () => {
  const [active, setActive] = useState(2)
  return (
    <div className="authincation d-flex flex-column flex-lg-row flex-column-fluid">
      <div className="login-aside text-center  d-flex flex-column flex-row-auto">
        <div className="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
          <div className="text-center mb-4 pt-5">
            <img src={logoFull} alt="" />
          </div>
          <h3 className="mb-2">Welcome back!</h3>
          <p>
            User Experience &amp; Interface Design <br />
            Strategy SaaS Solutions
          </p>
        </div>
        <Pic1
          width={360}
          height={450}
          className="aside-image"
        />
      </div>
      <div className="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
        <div className="d-flex justify-content-center h-100 align-items-center">
          <div className="authincation-content style-2">
            <div className="row no-gutters">
              <div className="col-xl-12 tab-content">
                {active === 1 && <SignUp onClick={() => setActive(2)} />}
                {active === 2 && (
                  <SignIn
                    onClick={() => setActive(3)}
                    onClick1={() => setActive(1)}
                  />
                )}
                {active === 3 && <ForgetPassword />}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Auth
