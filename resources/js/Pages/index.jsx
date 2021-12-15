import Layout from "../layouts/Layout";

const Home = () => <h1>Hello</h1>;

Home.layout = (page) => <Layout title="Home" children={page} />;

export default Home;
