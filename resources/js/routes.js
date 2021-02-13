import Index from './views/Index'

let routes = [
  {
    path: '/',
    component: Index,
    name: 'index',
    meta: { title: 'Home' }
  }
];

let router = new VueRouter({
  routes
});

export default router;
