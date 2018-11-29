<template>
    <div>
        <Row :gutter="16" style="margin-top: 16px">
            <ButtonGroup size="large">
                <Button @click="showdialog"><Icon type="ios-book"></Icon> 添加{{resourcetitle}}</Button>
                <Button @click="exportData()"><Icon type="ios-download-outline"></Icon> 导出{{resourcetitle}}列表</Button>
            </ButtonGroup>
        </Row>
        <Divider />
        <Table :columns="columns" :data="pageresources" ref="table"></Table>
        <Divider />
        <Page :total="resources.length" show-total @on-change="switchpage" />
        <Modal
            v-model="addmodal"
            :title="'添加' + resourcetitle"
            @on-ok="addresource">
            <Row :gutter="16" style="margin-top: 16px" v-for="(value, key, index) in resfields" :key="key">
                <Col span="6" offset="2" align="right">{{value}}：</Col>
                <template v-if="restypes[key] == 'textarea'">
                    <Col span="8"><Input v-model="resource[key]" type="textarea" :rows="4" style="width: auto" /></Col>
                </template>
                <template v-else-if="restypes[key] == 'datetime'">
                    <Col span="8">
                        <Input v-model="resource[key]" type="date" style="width: auto" />
                        <!-- <DatePicker type="date" v-model="resource[key]" ></DatePicker> -->
                    </Col>
                </template>
                <template v-else-if="restypes[key].indexOf('select') != -1">
                    <Col span="8">
                        <Select v-model="resource[key]">
                            <Option value="0">--请选择--</Option>
                            <Option v-for="item in selectops[key]" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </Col>
                </template>
                <template v-else>
                    <Col span="8"><Input v-model="resource[key]" style="width: auto" /></Col>
                </template>
            </Row>
        </Modal>
        <Modal v-model="delmodal">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>删除确认</span>
            </p>
            <div style="text-align:center">
                <p>您确认要删除该记录吗？</p>
            </div>
            <div slot="footer" style="text-align: center;">
                <Button type="error" @click="delresourceaction">删除</Button>
            </div>
        </Modal>
        <Modal
            v-model="editmodal"
            :title="'编辑' + resourcetitle"
            @on-ok="editresourceaction">
            <Row :gutter="16" style="margin-top: 16px" v-for="(value, key, index) in resfields" :key="key">
                <Col span="6" offset="2" align="right">{{value}}：</Col>
                <template v-if="restypes[key] == 'textarea'">
                    <Col span="8"><Input v-model="resource[key]" type="textarea" :rows="4" style="width: auto" /></Col>
                </template>
                <template v-else-if="restypes[key] == 'datetime'">
                    <Col span="8">
                        <Input v-model="resource[key]" type="date" style="width: auto" />
                        <!-- <DatePicker type="date" v-model="resource[key]" ></DatePicker> -->
                    </Col>
                </template>
                <template v-else-if="restypes[key].indexOf('select') != -1">
                    <Col span="8">
                        <Select v-model="resource[key]">
                            <Option value="0">--请选择--</Option>
                            <Option v-for="item in selectops[key]" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </Col>
                </template>
                <template v-else>
                    <Col span="8"><Input v-model="resource[key]" style="width: auto" /></Col>
                </template>
            </Row>
        </Modal>
    </div>
</template>

<script>
export default {
    name: 'ResourcesComponent',
    props: [
        'resourcename',
        'resourcetitle',
        'resourcefields',
        'resourcefieldtitles',
        'resourcefieldtypes'
    ],
    data() {
        return {
            columns: [
                {
                    title: '操作',
                    key: 'action',
                    align: 'center',
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.editresource(params.row.id)
                                    }
                                }
                            }, '编辑'),
                            h('Button', {
                                props: {
                                    type: 'error',
                                    size: 'small'
                                },
                                on: {
                                    click: () => {
                                        this.delresource(params.row.id)
                                    }
                                }
                            }, '删除')
                        ]);
                    }
                }
            ],
            resources: [],
            pagesize: 10,
            page: 1,
            addmodal: false,
            delmodal: false,
            editmodal: false,
            id: 0,
            resource: {},
            resfields: {},
            restypes: {},
            selectops: [],
            selectshows: []
        };
    },
    created() {
        // 初始化field-title
        for (let index = 0; index < this.resourcefields.length; index++) {
            const field = this.resourcefields[index];
            const title = this.resourcefieldtitles[index];
            this.resfields[field] = title;
            if (this.resourcefieldtypes) {
                const type = this.resourcefieldtypes[index];
                this.restypes[field] = type;
                // 下拉列表的处理
                const si = type.indexOf('select');
                if (si != -1) {
                    const parent = type.slice(7);
                    this.selectops[field] = [];
                    this.selectshows[field] = [];
                    // ajax获取服务器端数据
                    const app = this;
                    axios.get('/' + parent).then(function(response) {
                        app.selectops[field] = response.data;
                        // show信息
                        app.selectshows[field] = response.data;
                    });
                }
            } else {
                this.restypes[field] = 'text';
            }
        }
        // 初始化空资源对象
        this.initresource();
        // 修改columns属性
        // this.resourcefields.reverse();
        let index = 0;
        for (let key in this.resfields) {
            // if (this.resourcefieldtypes[key].indexOf('select') != -1) {
            //     var value = app.selectshows[field] = response.data;
            // }
            this.columns.splice(index++, 0, {
                title: this.resfields[key],
                key: key,
                sortable: true
            });
        }
        // this.resourcefields.reverse();
        // ajax获取资源列表
        const app = this;
        axios.get('/' + this.resourcename).then(function(response) {
            app.resources = response.data;
        });
    },
    computed: {
        // 分页显示资源
        pageresources() {
            var offset = (this.page - 1) * this.pagesize;
            return this.resources.slice(offset, offset + this.pagesize);
        }
    },
    methods: {
        // 初始化空资源对象
        initresource() {
            this.resourcefields.forEach(key => {
            var value = '';
            if (key == 'password') {
                value = '123456';
            }
            this.resource[key] = value;
        });
        },
        // 分页切换
        switchpage(p) {
            this.page = p;
        },
        // 下载excel模板
        downloadexcel() {
            const app = this;
            this.$refs.table.exportCsv({
                filename: this.resourcename + '-excel模板',
                columns: this.columns.filter((col, index) => index < app.columns.length - 1),
                data: this.resources.filter((data, index) => index < 2)
            });
        },
        // 文件上传回调函数
        uploadbefore(file) {
            console.log(file);
        },
        uploadsuccess(response, file, fileList) {
            console.log(response);
            this.$Notice.success({
                title: this.resourcetitle + '列表导入成功！',
                desc: ''
            });
        },
        uploaderror(error, file, fileList) {
            console.log(error);
            this.$Notice.error({
                title: this.resourcetitle + '列表导入失败！',
                desc: ''
            });
        },
        // 导出数据
        exportData () {
            this.$refs.table.exportCsv({
                filename: this.resourcename + '-list',
                original: false
            });
        },
        // 显示对话框
        showdialog() {
            // 清空resource状态
            this.initresource();
            this.addmodal = true;
        },
        // 添加资源
        addresource() {
            const app = this;
            axios.post('/' + this.resourcename, this.resource).then(function(response) {
                console.log(response);
                app.resources.push(response.data);
                // 清空resource状态
                app.initresource();
                // 给出提示消息
                app.$Notice.success({
                    title: '记录添加成功！',
                    desc: ''
                });
            });
        },
        // 删除资源
        delresource(index) {
            this.delmodal = true;
            this.id = index;
        },
        delresourceaction() {
            const app = this;
            // ajax删除
            axios.delete('/' + this.resourcename + '/' + this.id).then(function(response) {
                // 删除本地数据
                for (let i = 0; i < app.resources.length; i++) {
                    const element = app.resources[i];
                    if (element.id == app.id) {  // 找到了，删除之
                        app.resources.splice(i, 1);
                        break;
                    }
                }
                // 给出提示消息
                app.$Notice.success({
                    title: '记录删除成功！',
                    desc: ''
                });
            });
            // 关闭对话框
            this.delmodal = false;
        },
        // 编辑资源
        editresource(index) {
            this.editmodal = true;
            this.id = index;
            // 设置resource状态
            for (let i = 0; i < this.resources.length; i++) {
                const element = this.resources[i];
                if (element.id == index) {  // 找到了
                    this.resourcefields.forEach(key => {
                        this.resource[key] = element[key];
                    });
                    break;
                }
            }
        },
        editresourceaction() {
            const app = this;
            // ajax编辑
            axios.put('/' + this.resourcename + '/' + this.id, this.resource).then(function(response) {
                // 修改本地数据
                for (let i = 0; i < app.resources.length; i++) {
                    const element = app.resources[i];
                    if (element.id == app.id) {  // 找到了
                        app.resourcefields.forEach(key => {
                            app.resources[i][key] = app.resource[key];
                        });
                        break;
                    }
                }
                // 清空resource状态
                app.initresource();
                // 给出提示消息
                app.$Notice.success({
                    title: '记录编辑成功！',
                    desc: ''
                });
            });
            // 关闭对话框
            this.editmodal = false;
        }
    }
};
</script>
