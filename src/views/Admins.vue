<template>
    <div class="layout">
        <Layout>
            <Header :style="{padding: 0}" class="layout-header-bar">
                <Menu ref="headerMenu" mode="horizontal" theme="light" active-name="home">
                    <div class="layout-nav">
                        <MenuItem name="home" to="/">
                            <Icon type="ios-navigate"></Icon>
                            首页
                        </MenuItem>
                        <MenuItem name="about" to="/about">
                            <Icon type="ios-keypad"></Icon>
                            关于我们
                        </MenuItem>
                        <MenuItem name="3">
                            <Icon type="ios-analytics"></Icon>
                            Item 3
                        </MenuItem>
                        <MenuItem name="4">
                            <Icon type="ios-paper"></Icon>
                            Item 4
                        </MenuItem>
                    </div>
                </Menu>
            </Header>
            <Layout :style="{margin: '20px 0', background: '#f5f7f9', minHeight: '220px'}">
                <Sider ref="sidemenu" breakpoint="md" collapsible :collapsed-width="78" v-model="isCollapsed" :style="{background: '#f5f7f9'}">
                    <Menu active-name="1-2" theme="light" width="auto" :class="menuitemClasses">
                        <MenuItem name="1-1">
                            <Icon type="ios-navigate"></Icon>
                            <span>Option 1</span>
                        </MenuItem>
                        <MenuItem name="1-2">
                            <Icon type="ios-search"></Icon>
                            <span>Option 2</span>
                        </MenuItem>
                        <MenuItem name="1-3">
                            <Icon type="ios-settings"></Icon>
                            <span>Option 3</span>
                        </MenuItem>
                    </Menu>
                    <div slot="trigger"></Icon></div>
                </Sider>
                <Content :style="{marginLeft: '20px'}">
                    <Card>
                        <div style="min-height: 200px;">
                            <router-view></router-view>
                        </div>
                    </Card>
                </Content>
            </Layout>
            <Footer class="layout-footer-center">&copy; <a href="http://vue-think.hbynlsl.cn">vue-think</a></Footer>
        </Layout>
    </div>
</template>
<script>
    export default {
        name: 'Admins',
        data () {
            return {
                isCollapsed: false
            };
        },
        computed: {
            menuitemClasses: function () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        },
        methods: {
            
        }
    }
</script>
<style scoped>
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    padding: 0 50px;
}
.layout-header-bar{
    background: #f5f7f9;
    box-shadow: 0 1px 1px rgba(0,0,0,.1);
}
.menu-item span{
    display: inline-block;
    overflow: hidden;
    width: 69px;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
    transition: width .2s ease .2s;
}
.menu-item i{
    transform: translateX(0px);
    transition: font-size .2s ease, transform .2s ease;
    vertical-align: middle;
    font-size: 16px;
}
.collapsed-menu span{
    width: 0px;
    transition: width .2s ease;
}
.collapsed-menu i{
    transform: translateX(5px);
    transition: font-size .2s ease .2s, transform .2s ease .2s;
    vertical-align: middle;
    font-size: 22px;
}
</style>
