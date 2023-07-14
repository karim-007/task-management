<template>
<div class="wrapper">
  <v-card class="my-3">
    <v-breadcrumbs :items="breadcrums">
      <template v-slot:divider>
        <v-icon>mdi-chevron-right</v-icon>
      </template>
    </v-breadcrumbs>
  </v-card>
<v-container>
    <v-card>
      <v-card-title>
        App Users
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="users"
        :search="search"
      >

      </v-data-table>
  </v-card>
</v-container>

</div>
</template>
<script>
  export default {
    data: () => ({
      breadcrums: [
        {
          text: 'Dashboard',
          disabled: false,
          href: 'dashboard',
        },
        {
          text: 'App Users',
          disabled: true,
          href: '/profile',
        },

      ],
      search: '',
      headers: [
        {
          text: 'Dessert (100g serving)',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Calories', value: 'calories' },
        { text: 'Fat (g)', value: 'fat' },
        { text: 'Carbs (g)', value: 'carbs' },
        { text: 'Protein (g)', value: 'protein' },
        { text: 'Iron (%)', value: 'iron' },
      ],
      users: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%',
          },
        ],
    }),
  }


</script>
