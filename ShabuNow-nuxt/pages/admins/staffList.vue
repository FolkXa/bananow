<template>
<MainContainer>
  <HeaderContainer>
    <HeaderText>
      รายชื่อพนักงานสตาฟ
    </HeaderText>
  </HeaderContainer>
  <hr>
  <ContentContainer>
    <!-- Staff List -->
    <Table :datas="tableData" :headers="tableHeaders" class="mt-8">
      <template v-slot:title>
        พนักงานสตาฟทั้งหมด
      </template>
    </Table>

    <a 
    href="/admins/staff/create"
    class="">
      <Button class="w-full py-2 px-6">
        <i class="bi bi-person-plus-fill mr-2"></i>
        เพิ่มพนักงานสตาฟ
      </Button>
    </a>
      
  </ContentContainer>
</MainContainer>
</template>

<script>
export default {
  data() {
    return {
      // add some datas to table
      tableHeaders: [
        'Name',
      ],
      // add table headers
      tableData: [
        ['Prompan Uechanwech', 'Active'],
        ['Prompan Uechanwech', 'Active'],
        ['Prompan Uechanwech', 'Active'],
        ['Prompan Uechanwech', 'Active'],
        ['Prompan Uechanwech', 'Active'],
      ],
    }
  }
}
</script>
