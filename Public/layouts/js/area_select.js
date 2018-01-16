/**
 * 切换城市地区
 * @param _self 地域对象
 */
function areaChangeCallback(_self) {
    var parent_id = _self.value;
    var childName = $(_self).attr('child');

    if (!childName) {
        return;
    }

    //拆分子对象
    var childArray = childName.split(',');
    for (var index in childArray) {
        $childSelect = $('select[name="' + childArray[index] + '"]');
        $childSelect.empty();
    }

    //生成js联动菜单
    createAreaSelect(childArray[0], parent_id);
}

function createAreaSelect(name, parent_id, select_id) {
    if (city_url == '') {
        city_url = '/gift/Area/index.html';
    }
    if (name == "province_id") {
        parent_id = 0;
    } else if (parent_id < 1) {
        parent_id = -1;
    }

    $.ajax({
        url: city_url,
        type: "GET",
        dataType: "html",
        data: {parent_id: parent_id, select_id: select_id, name: name, "random": Math.random()},
        success: function (data) {
            $clickSelect = $('select[name="' + name + '"]');
            $clickSelect.html(data);
        }
    });
}