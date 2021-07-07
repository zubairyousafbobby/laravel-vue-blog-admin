<template>
<div>
    <div class="content">
        <div class="container-fluid">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
               <p class="_title0">Tags <Button @click="addModal=true"><Icon type="md-add" /> Add tag</Button></p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <tr>
                            <th>ID</th>
                            <th>Tag name</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                        <!-- TABLE TITLE -->

                        <!-- ITEMS -->
                        <tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
                            <td>{{tag.id}}</td>
                            <td class="_table_name">
                                {{tag.tagName}}
                            </td>
                            <td>{{tag.created_at}}</td>
                            <td>
                                <Button type="info" size="small" @click="showEditModal(tag, i)">Edit</Button>
                                <Button type="error" size="small" @click="showDeletingModal(tag, i)"  :loading="tag.isDeleting">Delete</Button>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        				<!-- tag adding modal -->
				<Modal
					v-model="addModal"
					title="Add tag"
					:mask-closable="false"
					:closable="false"

					>
					<Input v-model="data.tagName" placeholder="Add tag name"  />

					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add tag'}}</Button>
					</div>

				</Modal>

            <!-- tag editing modal -->
            <Modal v-model="editModal" title="Edit tag" :mask-closable="false" :closable="false">
                <Input v-model="editData.tagName" placeholder="Edit tag name" />

                <div slot="footer">
                    <Button type="default" @click="editModal=false">Close</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit tag'}}</Button>
                </div>

            </Modal>
            <deleteModal></deleteModal>

        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                tagName: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            tags: [],
            editData: {
                tagName: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleing: false,
            
            deleteItem: {},
            deletingIndex: -1,
            websiteSettings: []

        }
    },

    methods: {

        async addTag() {
            if (this.data.tagName.trim() == '') return this.e('Tag name is required')
            const res = await this.callApi('post', 'app/created_tag', this.data)
            if (res.status === 201) {
                this.tags.unshift(res.data)
                this.s('Tag has been added successfully!')
                this.addModal = false
                this.data.tagName = ''
            } else {
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        this.e(res.data.errors.tagName[0])
                    }

                } else {
                    this.swr()
                }

            }
        },

        async editTag() {
            if (this.editData.tagName.trim() == '') return this.e('Tag name is required')
            const res = await this.callApi('post', 'app/edit_tag', this.editData)
            if (res.status === 200) {
                this.tags[this.index].tagName = this.editData.tagName
                this.s('Tag has been edited successfully!')
                this.editModal = false

            } else {
                if (res.status == 422) {
                    if (res.data.errors.tagName) {
                        this.e(res.data.errors.tagName[0])
                    }

                } else {
                    this.swr()
                }

            }

        },
        async deleteTag(){
          this.isDeleing = true
          const res = await this.callApi('post', 'app/delete_tag', this.deleteItem)
			if(res.status===200){
				this.tags.splice(this.deletingIndex,1)
				this.s('Tag has been deleted successfully!')
			}else{
				this.swr()
			}
            this.isDeleing = false
			this.showDeleteModal = false
        },
        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName: tag.tagName
            }
            this.editData = obj
            this.editModal = true
            this.index = index
        },
          showDeletingModal(tag, i) {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_tag",
                data: tag,
                deletingIndex: i,
                isDeleted: false,
                msg : 'Are you sure you want to delete this blog?',
                successMsg: 'Blog has been deleted successfully!'
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
        },
    },

    async created() {
        const res = await this.callApi('get', 'app/get_tags', this.data)
        if (res.status == 200) {
            this.tags = res.data
            console.log(res.data);
        } else {
            this.swr()
        }

    }

};
</script>
