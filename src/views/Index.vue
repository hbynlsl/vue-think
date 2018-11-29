<template>
    <div class="layout">
        <Layout>
            <Header class="layout-header-bar">
              <Menu mode="horizontal" theme="light" active-name="home" width="auto">
                  <div class="layout-logo"></div>
                  <div class="layout-nav">
                      <MenuItem name="home" to="/">
                          <Icon type="ios-navigate"></Icon>
                          首页
                      </MenuItem>
                      <MenuItem name="about" to="/about">
                          <Icon type="ios-keypad"></Icon>
                          关于我们
                      </MenuItem>
                  </div>
              </Menu>
            </Header>
            <Content :style="{padding: '0 50px'}">
              <Card :style="{margin: '20px 0'}">
                  <div style="min-height: 200px;">
                      <router-view></router-view>
                  </div>
              </Card>
          </Content>
            <Footer class="layout-footer-center">&copy; <a href="http://vue-think.hbynlsl.cn">vue-think</a></Footer>
        </Layout>
    </div>
</template>
<script>
    export default {
        name: 'Index',
        data () {
            return {
                isCollapsed: true
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
        components: {

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