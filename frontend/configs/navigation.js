export default {
  // main navigation - side menu
  menu: [{
    text: "",
    key: "",
    items: [{
      icon: "mdi-view-dashboard-outline",
      key: "menu.dashboard",
      action: "read",
      subject: "Dashboard",
      text: "Dashboard",
      link: "/dashboard",
    },
      // { icon: 'mdi-file-outline', key: 'menu.blank', text: 'Blank Page', link: '/blank' }
    ],
  },
    {
      text: "",
      key: "",
      items: [{
        icon: "mdi-calendar-check",
        key: "menu.task",
        text: "Task",
        link: "/task",
        action: "read",
        subject: "Auth"
      },],
    },
  ],
  // footer links
  // footer: [{
  //   text: 'Docs',
  //   key: 'menu.docs',
  //   href: 'https://vuetifyjs.com',
  //   target: '_blank'
  // }]
};
